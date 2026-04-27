<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">📊 Activity Reports</h2>
                <p class="text-sm text-gray-500 mt-1">Query activity histories by custom date range</p>
            </div>
            <a href="{{ route('activities.index') }}" class="btn-secondary">← Back</a>
        </div>
    </x-slot>

    {{-- Filter Card --}}
    <div class="card mb-6" style="border-left: none; border-top: 4px solid #10b981;">
        <h3 class="text-lg font-bold text-slate-800 mb-4">🔍 Filter by Date Range</h3>
        <form method="GET" action="{{ route('activities.report') }}">
            <div class="flex gap-4 items-end flex-wrap">
                <div class="flex-1 min-w-40">
                    <label class="form-label">From</label>
                    <input type="date" name="from" value="{{ $from }}" class="form-input">
                </div>
                <div class="flex-1 min-w-40">
                    <label class="form-label">To</label>
                    <input type="date" name="to" value="{{ $to }}" class="form-input">
                </div>
                <div>
                    <button type="submit" class="btn-primary">
                        🔍 Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 text-center shadow-sm border-t-4 border-emerald-400">
            <p class="text-3xl font-bold text-slate-800">{{ $activities->count() }}</p>
            <p class="text-sm text-gray-500 mt-1">Total Activities</p>
        </div>
        <div class="bg-white rounded-xl p-4 text-center shadow-sm border-t-4 border-green-400">
            <p class="text-3xl font-bold text-green-600">
                {{ $activities->filter(fn($a) => $a->latestUpdate?->status === 'done')->count() }}
            </p>
            <p class="text-sm text-gray-500 mt-1">Completed</p>
        </div>
        <div class="bg-white rounded-xl p-4 text-center shadow-sm border-t-4 border-yellow-400">
            <p class="text-3xl font-bold text-yellow-600">
                {{ $activities->filter(fn($a) => $a->latestUpdate?->status === 'pending' || !$a->latestUpdate)->count() }}
            </p>
            <p class="text-sm text-gray-500 mt-1">Pending</p>
        </div>
    </div>

    {{-- Results Table --}}
    <div class="card" style="border-left: none; border-top: 4px solid #1e293b;">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-slate-800">Results</h3>
            <span class="badge-none">
                {{ \Carbon\Carbon::parse($from)->format('M j, Y') }} —
                {{ \Carbon\Carbon::parse($to)->format('M j, Y') }}
            </span>
        </div>

        @if($activities->isEmpty())
            <div class="text-center py-12">
                <div class="text-5xl mb-3">🔍</div>
                <p class="text-gray-400">No activities found for this period.</p>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                    <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Activity</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Category</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Date</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Updates</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Status</th>
                        <th style="padding: 12px 16px; text-align: center; font-weight: 600; color: #475569; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($activities as $index => $activity)
                        <tr style="border-bottom: 1px solid #f1f5f9; {{ $index % 2 == 0 ? 'background: white;' : 'background: #f8fafc;' }}"
                            onmouseover="this.style.background='#f0fdf4'"
                            onmouseout="this.style.background='{{ $index % 2 == 0 ? 'white' : '#f8fafc' }}'">
                            <td style="padding: 14px 16px;">
                                <p class="font-semibold text-slate-800">{{ $activity->title }}</p>
                                <p style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">
                                    {{ Str::limit($activity->description, 50) }}
                                </p>
                            </td>
                            <td style="padding: 14px 16px;">
                                    <span class="text-xs bg-emerald-50 text-emerald-700 px-2 py-1 rounded-full font-medium">
                                        {{ ucfirst($activity->category) }}
                                    </span>
                            </td>
                            <td style="padding: 14px 16px; color: #64748b; white-space: nowrap;">
                                {{ $activity->activity_date->format('M j, Y') }}
                            </td>
                            <td style="padding: 14px 16px;">
                                @if($activity->updates->isNotEmpty())
                                    <div class="space-y-1">
                                        @foreach($activity->updates->sortByDesc('updated_at_time')->take(2) as $update)
                                            <div style="font-size: 0.78rem; color: #4b5563;">
                                                <span style="font-weight: 600; color: #1e293b;">{{ $update->user->name }}</span>
                                                — {{ Str::limit($update->remark ?? 'No remark', 30) }}
                                                <span style="color: #94a3b8;">
                                                        ({{ $update->updated_at_time->format('h:i A') }})
                                                    </span>
                                            </div>
                                        @endforeach
                                        @if($activity->updates->count() > 2)
                                            <p style="font-size: 0.75rem; color: #10b981;">
                                                +{{ $activity->updates->count() - 2 }} more
                                            </p>
                                        @endif
                                    </div>
                                @else
                                    <span style="color: #94a3b8; font-size: 0.85rem;">No updates</span>
                                @endif
                            </td>
                            <td style="padding: 14px 16px;">
                                @if($activity->latestUpdate)
                                    @if($activity->latestUpdate->status === 'done')
                                        <span class="badge-done">✅ Done</span>
                                    @else
                                        <span class="badge-pending">⏳ Pending</span>
                                    @endif
                                @else
                                    <span class="badge-none">— None</span>
                                @endif
                            </td>
                            <td style="padding: 14px 16px; text-align: center;">
                                <a href="{{ route('activities.show', $activity) }}"
                                   style="color: #10b981; font-size: 0.85rem; font-weight: 600; text-decoration: none;"
                                   onmouseover="this.style.textDecoration='underline'"
                                   onmouseout="this.style.textDecoration='none'">
                                    View →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</x-app-layout>
