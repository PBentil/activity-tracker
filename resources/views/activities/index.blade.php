<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p class="page-title">Daily Activities</p>
                <p class="page-subtitle">{{ today()->format('l, F j, Y') }}</p>
            </div>
            <div style="display: flex; gap: 0.75rem;">
                <a href="{{ route('activities.report') }}" class="btn-secondary">Reports</a>
                <button onclick="document.getElementById('activityModal').classList.remove('hidden')" class="btn-primary">
                    New Activity
                </button>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if($activities->isEmpty())
        <div style="text-align: center; padding: 5rem 0; color: #9ca3af;">
            <p style="font-size: 0.9rem; font-weight: 500; color: #374151; margin-bottom: 0.5rem;">No activities for today</p>
            <p style="font-size: 0.82rem; margin-bottom: 1.5rem;">Start by adding your first activity for the day.</p>
            <button onclick="document.getElementById('activityModal').classList.remove('hidden')" class="btn-primary">
                New Activity
            </button>
        </div>
    @else
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

        @php
            $pendingActivities = $activities->filter(fn($a) => $a->latestUpdate?->status === 'pending' || !$a->latestUpdate);
        @endphp

        @if($pendingActivities->isNotEmpty())
            <div class="handover-section" id="handoverSection">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                    <div>
                        <p class="handover-title">Handover — Pending Activities</p>
                        <p class="handover-subtitle">These activities must be handed over to the next shift.</p>
                    </div>
                    <button onclick="printHandover()" class="btn-secondary no-print" style="font-size: 0.78rem; padding: 6px 14px;">
                        Print / Export
                    </button>
                </div>
                <div style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                        <tr>
                            <th>Activity</th>
                            <th>Category</th>
                            <th>Last Updated By</th>
                            <th>Remark</th>
                            <th>Time</th>
                            <th class="no-print">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pendingActivities as $activity)
                            <tr>
                                <td style="font-weight: 500; color: #111827;">{{ $activity->title }}</td>
                                <td><span class="tag">{{ ucfirst($activity->category) }}</span></td>
                                <td>
                                    @if($activity->latestUpdate)
                                        <p style="font-weight: 500; font-size: 0.8rem; color: #111827;">{{ $activity->latestUpdate->user->name }}</p>
                                        <p style="font-size: 0.72rem; color: #9ca3af;">{{ $activity->latestUpdate->user->email }}</p>
                                    @else
                                        <span style="color: #9ca3af; font-size: 0.8rem;">Not yet updated</span>
                                    @endif
                                </td>
                                <td style="color: #6b7280;">{{ $activity->latestUpdate?->remark ?? '—' }}</td>
                                <td style="white-space: nowrap; color: #9ca3af; font-size: 0.78rem;">
                                    {{ $activity->latestUpdate?->updated_at_time?->format('h:i A') ?? '—' }}
                                </td>
                                <td class="no-print">
                                    <a href="{{ route('activities.show', $activity) }}" class="table-link">Pick up</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif


        <div class="card">
            <p class="section-title">All Activities</p>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>Activity</th>
                        <th>Category</th>
                        <th>Last Updated By</th>
                        <th>Remark</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($activities->sortBy(fn($a) => $a->latestUpdate?->status === 'done' ? 1 : 0) as $activity)
                        <tr>
                            <td>
                                <p style="font-weight: 500; color: #111827; font-size: 0.82rem;">{{ $activity->title }}</p>
                                <p style="font-size: 0.72rem; color: #9ca3af;">{{ Str::limit($activity->description, 50) }}</p>
                            </td>
                            <td><span class="tag">{{ ucfirst($activity->category) }}</span></td>
                            <td>
                                @if($activity->latestUpdate)
                                    <p style="font-weight: 500; font-size: 0.8rem; color: #111827;">{{ $activity->latestUpdate->user->name }}</p>
                                    <p style="font-size: 0.72rem; color: #9ca3af;">{{ $activity->latestUpdate->user->email }}</p>
                                @else
                                    <span style="color: #9ca3af; font-size: 0.8rem;">—</span>
                                @endif
                            </td>
                            <td style="color: #6b7280; font-size: 0.8rem;">{{ Str::limit($activity->latestUpdate?->remark ?? '—', 40) }}</td>
                            <td style="white-space: nowrap; color: #9ca3af; font-size: 0.78rem;">
                                {{ $activity->latestUpdate?->updated_at_time?->format('h:i A') ?? '—' }}
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
        </div>
    @endif

    <div id="activityModal" style="display:none;" class="modal-overlay" onclick="if(event.target===this)this.style.display='none'">
        <div class="modal-box">
            <div class="modal-header">
                <span class="modal-title">New Activity</span>
                <button onclick="document.getElementById('activityModal').style.display='none'" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                @if($errors->any())
                    <div class="alert-error">
                        <ul style="list-style: disc; padding-left: 1rem;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('activities.store') }}">
                    @csrf
                    <div style="margin-bottom: 1rem;">
                        <label class="form-label">Activity Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-input" placeholder="e.g. Daily SMS count vs logs">
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-input">
                            <option value="">Select category</option>
                            <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>General</option>
                            <option value="sms" {{ old('category') == 'sms' ? 'selected' : '' }}>SMS</option>
                            <option value="logs" {{ old('category') == 'logs' ? 'selected' : '' }}>Logs</option>
                            <option value="monitoring" {{ old('category') == 'monitoring' ? 'selected' : '' }}>Monitoring</option>
                            <option value="incident" {{ old('category') == 'incident' ? 'selected' : '' }}>Incident</option>
                            <option value="change" {{ old('category') == 'change' ? 'selected' : '' }}>Change Management</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3" class="form-input" placeholder="Describe the activity...">{{ old('description') }}</textarea>
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label class="form-label">Activity Date</label>
                        <input type="date" name="activity_date" value="{{ old('activity_date', today()->toDateString()) }}" class="form-input">
                    </div>
                    <div style="display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid #f3f4f6;">
                        <button type="button" onclick="document.getElementById('activityModal').classList.add('hidden')" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-primary">Save Activity</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($errors->hasAny(['title', 'category', 'activity_date']))
        <script>document.getElementById('activityModal').style.display='flex';</script>
    @endif
    <script>
        function printHandover() {
            const printArea = document.getElementById('handoverSection');
            printArea.id = 'handoverPrintArea';
            const header = document.createElement('div');
            header.id = 'printHeader';
            header.innerHTML = `
                <div style="text-align:center; margin-bottom:20px; border-bottom:1px solid #e5e7eb; padding-bottom:12px;">
                    <h2 style="font-size:1.1rem; font-weight:600; color:#111827;">Activity Tracker — Handover Report</h2>
                    <p style="color:#6b7280; font-size:0.8rem; margin-top:4px;">Date: {{ today()->format('l, F j, Y') }}</p>
                    <p style="color:#6b7280; font-size:0.8rem;">Generated by: {{ auth()->user()->name }}</p>
                </div>`;
            printArea.prepend(header);
            window.print();
            printArea.id = 'handoverSection';
            document.getElementById('printHeader').remove();
        }
    </script>
</x-app-layout>
