<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p class="page-title">{{ $activity->title }}</p>
                <p class="page-subtitle">{{ ucfirst($activity->category) }} — {{ $activity->activity_date->format('F j, Y') }}</p>
            </div>
            <div style="display: flex; gap: 0.75rem;">
                <a href="{{ route('activities.index') }}" class="btn-secondary">Back</a>
                <button onclick="document.getElementById('updateModal').style.display='flex'" class="btn-primary">
                    Update Status
                </button>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="card" style="margin-bottom: 1.5rem;">
        <p class="section-title">Activity Details</p>
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem;">
            <div>
                <p style="font-size: 0.72rem; color: #9ca3af; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Category</p>
                <span class="tag">{{ ucfirst($activity->category) }}</span>
            </div>
            <div>
                <p style="font-size: 0.72rem; color: #9ca3af; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Date</p>
                <p style="font-size: 0.85rem; font-weight: 500; color: #111827;">{{ $activity->activity_date->format('F j, Y') }}</p>
            </div>
            <div>
                <p style="font-size: 0.72rem; color: #9ca3af; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Updates</p>
                <p style="font-size: 0.85rem; font-weight: 500; color: #111827;">{{ $activity->updates->count() }}</p>
            </div>
            <div>
                <p style="font-size: 0.72rem; color: #9ca3af; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Status</p>
                @if($activity->latestUpdate?->status === 'done')
                    <span class="badge-done">Done</span>
                @elseif($activity->latestUpdate?->status === 'pending')
                    <span class="badge-pending">Pending</span>
                @else
                    <span class="badge-none">No update</span>
                @endif
            </div>
        </div>
        @if($activity->description)
            <div style="margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px solid #f3f4f6;">
                <p style="font-size: 0.72rem; color: #9ca3af; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Description</p>
                <p style="font-size: 0.85rem; color: #374151;">{{ $activity->description }}</p>
            </div>
        @endif
    </div>

    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <p class="section-title" style="margin-bottom: 0;">Update History</p>
            <span class="badge-none">{{ $activity->updates->count() }} update(s)</span>
        </div>

        @if($activity->updates->isEmpty())
            <div style="text-align: center; padding: 3rem 0; color: #9ca3af;">
                <p style="font-size: 0.85rem;">No updates yet.</p>
                <p style="font-size: 0.78rem; margin-top: 4px;">Click "Update Status" to add the first update.</p>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                    <tr>
                        <th>Personnel</th>
                        <th>Status</th>
                        <th>Remark</th>
                        <th>Time</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($activity->updates->sortByDesc('updated_at_time') as $update)
                        <tr>
                            <td>
                                <p style="font-weight: 500; font-size: 0.82rem; color: #111827;">{{ $update->user->name }}</p>
                                <p style="font-size: 0.72rem; color: #9ca3af;">{{ $update->user->email }}</p>
                            </td>
                            <td>
                                @if($update->status === 'done')
                                    <span class="badge-done">Done</span>
                                @else
                                    <span class="badge-pending">Pending</span>
                                @endif
                            </td>
                            <td style="color: #6b7280; font-size: 0.82rem;">{{ $update->remark ?? '—' }}</td>
                            <td style="white-space: nowrap; color: #9ca3af; font-size: 0.78rem;">
                                {{ $update->updated_at_time->format('M j, Y h:i A') }}
                            </td>
                            <td>
                                <form method="POST" action="{{ route('activity-updates.destroy', $update) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn" onclick="return confirm('Delete this update?')">
                                        Delete
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

    <div id="updateModal" style="display:none;" class="modal-overlay" onclick="if(event.target===this)this.style.display='none'">
        <div class="modal-box">
            <div class="modal-header">
                <span class="modal-title">Update Activity Status</span>
                <button onclick="document.getElementById('updateModal').style.display='none'" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 0.75rem 1rem; margin-bottom: 1.25rem;">
                    <p style="font-size: 0.82rem; font-weight: 500; color: #111827;">{{ $activity->title }}</p>
                    <p style="font-size: 0.75rem; color: #9ca3af; margin-top: 2px;">{{ $activity->activity_date->format('F j, Y') }}</p>
                </div>
                <form method="POST" action="{{ route('activity-updates.store', $activity) }}">
                    @csrf
                    <div style="margin-bottom: 1rem;">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-input">
                            <option value="pending">Pending</option>
                            <option value="done">Done</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label class="form-label">Remark</label>
                        <textarea name="remark" rows="4" class="form-input" placeholder="Add a remark about this update..."></textarea>
                    </div>
                    <div style="display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid #f3f4f6;">
                        <button type="button" onclick="document.getElementById('updateModal').style.display='none'" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-primary">Submit Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
