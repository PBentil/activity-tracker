<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">{{ $activity->title }}</h2>
                <p class="text-sm text-gray-500 mt-1">
                    {{ ucfirst($activity->category) }} — {{ $activity->activity_date->format('F j, Y') }}
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('activities.index') }}" class="btn-secondary">← Back</a>
                <button onclick="document.getElementById('updateModal').classList.remove('hidden')"
                        class="btn-primary">
                    🔄 Update Status
                </button>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif

    {{-- Activity Details Card --}}
    <div class="card mb-6" style="border-left: none; border-top: 4px solid #10b981;">
        <h3 class="text-lg font-bold text-slate-800 mb-4">📋 Activity Details</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Category</p>
                <span class="text-xs bg-emerald-50 text-emerald-700 px-2 py-1 rounded-full font-medium mt-1 inline-block">
                    {{ ucfirst($activity->category) }}
                </span>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Date</p>
                <p class="text-gray-800 font-medium mt-1">{{ $activity->activity_date->format('F j, Y') }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Total Updates</p>
                <p class="text-gray-800 font-medium mt-1">{{ $activity->updates->count() }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Current Status</p>
                <div class="mt-1">
                    @if($activity->latestUpdate)
                        @if($activity->latestUpdate->status === 'done')
                            <span class="badge-done">✅ Done</span>
                        @else
                            <span class="badge-pending">⏳ Pending</span>
                        @endif
                    @else
                        <span class="badge-none">— No Update Yet</span>
                    @endif
                </div>
            </div>
        </div>
        @if($activity->description)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Description</p>
                <p class="text-gray-600 mt-1">{{ $activity->description }}</p>
            </div>
        @endif
    </div>

    {{-- Update History Table --}}
    <div class="card" style="border-left: none; border-top: 4px solid #1e293b;">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-slate-800">📜 Update History</h3>
            <span class="badge-none">{{ $activity->updates->count() }} update(s)</span>
        </div>

        @if($activity->updates->isEmpty())
            <div class="text-center py-12">
                <div class="text-5xl mb-3">📭</div>
                <p class="text-gray-400">No updates yet. Click "Update Status" to add the first update.</p>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                    <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Personnel</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Status</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Remark</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #475569; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Time</th>
                        <th style="padding: 12px 16px; text-align: center; font-weight: 600; color: #475569; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($activity->updates->sortByDesc('updated_at_time') as $index => $update)
                        <tr style="border-bottom: 1px solid #f1f5f9; {{ $index % 2 == 0 ? 'background: white;' : 'background: #f8fafc;' }} transition: background 0.15s;"
                            onmouseover="this.style.background='#f0fdf4'"
                            onmouseout="this.style.background='{{ $index % 2 == 0 ? 'white' : '#f8fafc' }}'">
                            <td style="padding: 14px 16px;">
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $update->user->name }}</p>
                                    <p style="font-size: 0.75rem; color: #94a3b8;">{{ $update->user->email }}</p>
                                </div>
                            </td>
                            <td style="padding: 14px 16px;">
                                @if($update->status === 'done')
                                    <span class="badge-done">✅ Done</span>
                                @else
                                    <span class="badge-pending">⏳ Pending</span>
                                @endif
                            </td>
                            <td style="padding: 14px 16px; color: #4b5563; max-width: 250px;">
                                {{ $update->remark ?? '—' }}
                            </td>
                            <td style="padding: 14px 16px; color: #64748b; white-space: nowrap; font-size: 0.85rem;">
                                🕐 {{ $update->updated_at_time->format('M j, Y h:i A') }}
                            </td>
                            <td style="padding: 14px 16px; text-align: center;">
                                <form method="POST"
                                      action="{{ route('activity-updates.destroy', $update) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            style="color: #ef4444; font-size: 0.8rem; background: none; border: none; cursor: pointer;"
                                            onmouseover="this.style.textDecoration='underline'"
                                            onmouseout="this.style.textDecoration='none'"
                                            onclick="return confirm('Delete this update?')">
                                        🗑 Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Update Modal --}}
    <div id="updateModal" class="hidden fixed inset-0 z-50 flex items-center justify-center"
         style="background: rgba(0,0,0,0.5);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">

            {{-- Modal Header --}}
            <div class="flex justify-between items-center px-6 py-4" style="background: #1e293b;">
                <h3 class="text-white font-bold text-lg">🔄 Update Activity Status</h3>
                <button onclick="document.getElementById('updateModal').classList.add('hidden')"
                        class="text-gray-400 hover:text-white text-2xl leading-none">&times;</button>
            </div>

            {{-- Modal Body --}}
            <div class="px-6 py-6">
                <div class="bg-emerald-50 rounded-lg p-3 mb-5">
                    <p class="text-sm font-semibold text-emerald-800">{{ $activity->title }}</p>
                    <p class="text-xs text-emerald-600 mt-1">{{ $activity->activity_date->format('F j, Y') }}</p>
                </div>

                <form method="POST" action="{{ route('activity-updates.store', $activity) }}">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-input">
                            <option value="pending">⏳ Pending</option>
                            <option value="done">✅ Done</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="form-label">Remark</label>
                        <textarea name="remark" rows="4"
                                  class="form-input"
                                  placeholder="Add a remark about this update..."></textarea>
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                        <button type="button"
                                onclick="document.getElementById('updateModal').classList.add('hidden')"
                                class="text-gray-400 hover:text-gray-600 text-sm">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary">
                            Submit Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
