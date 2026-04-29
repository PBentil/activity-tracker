<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p class="page-title">Activity Reports</p>
                <p class="page-subtitle">Query activity histories by custom date range</p>
            </div>
            <a href="{{ route('activities.index') }}" class="btn-secondary">Back</a>
        </div>
    </x-slot>

    <div class="card" style="margin-bottom: 1.5rem;">
        <p class="section-title">Filter by Date Range</p>
        <form method="GET" action="{{ route('activities.report') }}">
            <div style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 160px;">
                    <label class="form-label">From</label>
                    <input type="date" name="from" value="{{ $from }}" class="form-input">
                </div>
                <div style="flex: 1; min-width: 160px;">
                    <label class="form-label">To</label>
                    <input type="date" name="to" value="{{ $to }}" class="form-input">
                </div>
                <div>
                    <button type="submit" class="btn-primary">Apply Filter</button>
                </div>
            </div>
        </form>
    </div>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
        <div class="stat-card">
            <p class="stat-number">{{ $activities->count() }}</p>
            <p class="stat-label">Total Activities</p>
        </div>
        <div class="stat-card">
            <p class="stat-number" style="color: #15803d;">
                {{ $activities->filter(fn($a) => $a->latestUpdate?->status === 'done')->count() }}
            </p>
            <p class="stat-label">Completed</p>
        </div>
        <div class="stat-card">
            <p class="stat-number" style="color: #b45309;">
                {{ $activities->filter(fn($a) => $a->latestUpdate?->status !== 'done')->count() }}
            </p>
            <p class="stat-label">Pending</p>
        </div>
    </div>

    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <p class="section-title" style="margin-bottom: 0;">Results</p>
            <span class="badge-none" style="font-size: 0.72rem;">
                {{ \Carbon\Carbon::parse($from)->format('M j, Y') }} — {{ \Carbon\Carbon::parse($to)->format('M j, Y') }}
            </span>
        </div>

        @if($activities->isEmpty())
            <div style="text-align: center; padding: 3rem 0; color: #9ca3af;">
                <p style="font-size: 0.85rem;">No activities found for this period.</p>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>Activity</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Last Update</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($activities as $activity)
                        <tr>
                            <td>
                                <p style="font-weight: 500; font-size: 0.82rem; color: #111827;">{{ $activity->title }}</p>
                                <p style="font-size: 0.72rem; color: #9ca3af;">{{ Str::limit($activity->description, 50) }}</p>
                            </td>
                            <td><span class="tag">{{ ucfirst($activity->category) }}</span></td>
                            <td style="white-space: nowrap; font-size: 0.8rem; color: #6b7280;">
                                {{ $activity->activity_date->format('M j, Y') }}
                            </td>
                            <td>
                                @if($activity->latestUpdate)
                                    <p style="font-size: 0.8rem; font-weight: 500; color: #111827;">{{ $activity->latestUpdate->user->name }}</p>
                                    <p style="font-size: 0.72rem; color: #9ca3af;">
                                        {{ Str::limit($activity->latestUpdate->remark ?? '—', 35) }}
                                    </p>
                                @else
                                    <span style="color: #9ca3af; font-size: 0.8rem;">No updates</span>
                                @endif
                            </td>
                            <td>
                                @if($activity->latestUpdate?->status === 'done')
                                    <span class="badge-done">Done</span>
                                @elseif($activity->latestUpdate?->status === 'pending')
                                    <span class="badge-pending">Pending</span>
                                @else
                                    <span class="badge-none">No update</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('activities.show', $activity) }}" class="table-link">View</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</x-app-layout>
