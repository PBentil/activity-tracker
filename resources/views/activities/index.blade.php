<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Daily Activities</h2>
                <p class="text-sm text-gray-500 mt-1">{{ today()->format('l, F j, Y') }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('activities.report') }}" class="btn-secondary">
                    📊 Reports
                </a>
                <button onclick="document.getElementById('activityModal').classList.remove('hidden')" class="btn-primary">
                    + New Activity
                </button>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif

    @if($activities->isEmpty())
        <div class="text-center py-20">
            <div class="text-6xl mb-4">📋</div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No activities for today</h3>
            <p class="text-gray-400 mb-6">Start by adding your first activity for the day.</p>
            <button onclick="document.getElementById('activityModal').classList.remove('hidden')" class="btn-primary">
                + New Activity
            </button>
        </div>
    @else
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
                <p class="text-sm text-gray-500 mt-1">Done</p>
            </div>
            <div class="bg-white rounded-xl p-4 text-center shadow-sm border-t-4 border-yellow-400">
                <p class="text-3xl font-bold text-yellow-600">
                    {{ $activities->filter(fn($a) => $a->latestUpdate?->status === 'pending' || !$a->latestUpdate)->count() }}
                </p>
                <p class="text-sm text-gray-500 mt-1">Pending</p>
            </div>
        </div>

        {{-- Handover Section --}}
        @php
            $pendingActivities = $activities->filter(fn($a) => $a->latestUpdate?->status === 'pending' || !$a->latestUpdate);
        @endphp

        @if($pendingActivities->isNotEmpty())
            <div class="mb-6" id="handoverSection" style="border: 2px dashed #f59e0b; border-radius: 12px; padding: 1.5rem; background: #fffbeb;">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">⚠️</span>
                        <div>
                            <h3 class="text-lg font-bold text-yellow-800">Handover — Pending Activities</h3>
                            <p class="text-sm text-yellow-600">These activities require attention and must be handed over to the next shift.</p>
                        </div>
                    </div>
                    <button onclick="printHandover()"
                            class="no-print"
                            style="background: #1e293b; color: white; padding: 8px 16px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; border: none; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                        🖨️ Print / Export PDF
                    </button>
                </div>

                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                        <thead>
                        <tr style="background: #fef3c7; border-bottom: 2px solid #f59e0b;">
                            <th style="padding: 10px 14px; text-align: left; font-weight: 600; color: #92400e; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Activity</th>
                            <th style="padding: 10px 14px; text-align: left; font-weight: 600; color: #92400e; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Category</th>
                            <th style="padding: 10px 14px; text-align: left; font-weight: 600; color: #92400e; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Last Updated By</th>
                            <th style="padding: 10px 14px; text-align: left; font-weight: 600; color: #92400e; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Last Remark</th>
                            <th style="padding: 10px 14px; text-align: left; font-weight: 600; color: #92400e; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Time</th>
                            <th style="padding: 10px 14px; text-align: center; font-weight: 600; color: #92400e; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;" class="no-print">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pendingActivities as $activity)
                            <tr style="border-bottom: 1px solid #fde68a; background: white;"
                                onmouseover="this.style.background='#fef9c3'"
                                onmouseout="this.style.background='white'">
                                <td style="padding: 12px 14px;">
                                    <p class="font-semibold text-slate-800">{{ $activity->title }}</p>
                                </td>
                                <td style="padding: 12px 14px;">
                                        <span class="text-xs bg-emerald-50 text-emerald-700 px-2 py-1 rounded-full font-medium">
                                            {{ ucfirst($activity->category) }}
                                        </span>
                                </td>
                                <td style="padding: 12px 14px;">
                                    @if($activity->latestUpdate)
                                        <p class="font-semibold text-slate-700">{{ $activity->latestUpdate->user->name }}</p>
                                        <p style="font-size: 0.75rem; color: #94a3b8;">{{ $activity->latestUpdate->user->email }}</p>
                                    @else
                                        <span style="color: #94a3b8; font-size: 0.85rem;">Not yet updated</span>
                                    @endif
                                </td>
                                <td style="padding: 12px 14px; color: #4b5563; max-width: 200px;">
                                    {{ $activity->latestUpdate?->remark ?? '—' }}
                                </td>
                                <td style="padding: 12px 14px; color: #64748b; white-space: nowrap; font-size: 0.85rem;">
                                    @if($activity->latestUpdate)
                                        🕐 {{ $activity->latestUpdate->updated_at_time->format('h:i A') }}
                                    @else
                                        <span style="color: #94a3b8;">—</span>
                                    @endif
                                </td>
                                <td style="padding: 12px 14px; text-align: center;" class="no-print">
                                    <a href="{{ route('activities.show', $activity) }}"
                                       style="color: #10b981; font-size: 0.85rem; font-weight: 600; text-decoration: none;"
                                       onmouseover="this.style.textDecoration='underline'"
                                       onmouseout="this.style.textDecoration='none'">
                                        Pick Up →
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- All Activities --}}
        <div class="card" style="border-left: none; border-top: 4px solid #1e293b;">
            <h3 class="text-lg font-bold text-slate-800 mb-4">📋 All Activities Today</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                    <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Activity</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Category</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Last Updated By</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Last Remark</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Time</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Status</th>
                        <th style="padding: 12px 16px; text-align: center; font-weight: 600; color: #475569; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($activities->sortBy(fn($a) => $a->latestUpdate?->status === 'done' ? 1 : 0) as $index => $activity)
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
                            <td style="padding: 14px 16px;">
                                @if($activity->latestUpdate)
                                    <p class="font-semibold text-slate-700">{{ $activity->latestUpdate->user->name }}</p>
                                    <p style="font-size: 0.75rem; color: #94a3b8;">{{ $activity->latestUpdate->user->email }}</p>
                                @else
                                    <span style="color: #94a3b8; font-size: 0.85rem;">Not yet updated</span>
                                @endif
                            </td>
                            <td style="padding: 14px 16px; color: #4b5563; max-width: 200px;">
                                {{ Str::limit($activity->latestUpdate?->remark ?? '—', 40) }}
                            </td>
                            <td style="padding: 14px 16px; color: #64748b; white-space: nowrap; font-size: 0.85rem;">
                                @if($activity->latestUpdate)
                                    🕐 {{ $activity->latestUpdate->updated_at_time->format('h:i A') }}
                                @else
                                    <span style="color: #94a3b8;">—</span>
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
                                    <span class="badge-none">— No Update</span>
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
        </div>
    @endif

    {{-- New Activity Modal --}}
    <div id="activityModal" class="hidden fixed inset-0 z-50 flex items-center justify-center"
         style="background: rgba(0,0,0,0.5);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
            <div class="flex justify-between items-center px-6 py-4" style="background: #1e293b;">
                <h3 class="text-white font-bold text-lg">➕ New Activity</h3>
                <button onclick="document.getElementById('activityModal').classList.add('hidden')"
                        class="text-gray-400 hover:text-white text-2xl leading-none">&times;</button>
            </div>
            <div class="px-6 py-6">
                @if($errors->any())
                    <div class="alert-error mb-4">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('activities.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Activity Title *</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               class="form-input"
                               placeholder="e.g. Daily SMS count vs logs">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Category *</label>
                        <select name="category" class="form-input">
                            <option value="">Select a category</option>
                            <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>General</option>
                            <option value="sms" {{ old('category') == 'sms' ? 'selected' : '' }}>SMS</option>
                            <option value="logs" {{ old('category') == 'logs' ? 'selected' : '' }}>Logs</option>
                            <option value="monitoring" {{ old('category') == 'monitoring' ? 'selected' : '' }}>Monitoring</option>
                            <option value="incident" {{ old('category') == 'incident' ? 'selected' : '' }}>Incident</option>
                            <option value="change" {{ old('category') == 'change' ? 'selected' : '' }}>Change Management</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3"
                                  class="form-input"
                                  placeholder="Describe the activity...">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-6">
                        <label class="form-label">Activity Date *</label>
                        <input type="date" name="activity_date"
                               value="{{ old('activity_date', today()->toDateString()) }}"
                               class="form-input">
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                        <button type="button"
                                onclick="document.getElementById('activityModal').classList.add('hidden')"
                                class="text-gray-400 hover:text-gray-600 text-sm">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary">💾 Save Activity</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($errors->any())
        <script>
            document.getElementById('activityModal').classList.remove('hidden');
        </script>
    @endif

    {{-- Print Styles --}}
    <style>
        @media print {
            body * { visibility: hidden; }
            #handoverPrintArea, #handoverPrintArea * { visibility: visible; }
            #handoverPrintArea {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                padding: 20px;
            }
            .no-print { display: none !important; }
        }
    </style>

    {{-- Print Script --}}
    <script>
        function printHandover() {
            const printArea = document.getElementById('handoverSection');
            printArea.id = 'handoverPrintArea';

            const header = document.createElement('div');
            header.id = 'printHeader';
            header.innerHTML = `
                <div style="text-align: center; margin-bottom: 20px; border-bottom: 2px solid #f59e0b; padding-bottom: 12px;">
                    <h2 style="font-size: 1.5rem; font-weight: 700; color: #1e293b;">Activity Tracker — Handover Report</h2>
                    <p style="color: #64748b; font-size: 0.9rem;">Date: {{ today()->format('l, F j, Y') }}</p>
                    <p style="color: #64748b; font-size: 0.9rem;">Generated by: {{ auth()->user()->name }}</p>
                </div>
            `;
            printArea.prepend(header);

            window.print();

            printArea.id = 'handoverSection';
            document.getElementById('printHeader').remove();
        }
    </script>

</x-app-layout>
