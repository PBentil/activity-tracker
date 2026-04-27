<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Daily Activities</h2>
                <p class="text-sm text-gray-500 mt-1">{{ today()->format('l, F j, Y') }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('activities.report') }}" class="btn-secondary">
                    Reports
                </a>
                <button onclick="document.getElementById('activityModal').classList.remove('hidden')" class="btn-primary">
                    + New Activity
                </button>
            </div>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
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


        <div class="space-y-4">
            @foreach($activities as $activity)
                <div class="card">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="text-lg font-bold text-slate-800">{{ $activity->title }}</h3>
                                <span class="text-xs bg-emerald-50 text-emerald-700 px-2 py-1 rounded-full font-medium">
                                    {{ ucfirst($activity->category) }}
                                </span>
                            </div>
                            <p class="text-gray-500 text-sm">{{ $activity->description }}</p>
                        </div>
                        <div class="ml-4">
                            @if($activity->latestUpdate)
                                @if($activity->latestUpdate->status === 'done')
                                    <span class="badge-done"> Done</span>
                                @else
                                    <span class="badge-pending">⏳ Pending</span>
                                @endif
                            @else
                                <span class="badge-none">— No Update</span>
                            @endif
                        </div>
                    </div>

                    @if($activity->updates->isNotEmpty())
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Recent Updates</p>
                            <div class="space-y-2">
                                @foreach($activity->updates->take(3) as $update)
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">
                                            <span class="font-semibold text-slate-700">{{ $update->user->name }}</span>
                                            — {{ $update->remark ?? 'No remark' }}
                                        </span>
                                        <span class="text-gray-400 text-xs">
                                            {{ $update->updated_at_time->format('h:i A') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-4 pt-3 border-t border-gray-100">
                        <a href="{{ route('activities.show', $activity) }}" class="btn-primary text-sm">
                            View & Update →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div id="activityModal" class="hidden fixed inset-0 z-50 flex items-center justify-center"
         style="background: rgba(0,0,0,0.5);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">

            <div class="flex justify-between items-center px-6 py-4" style="background: #1e293b;">
                <h3 class="text-white font-bold text-lg">New Activity</h3>
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
                        <button type="submit" class="btn-primary">
                             Save Activity
                        </button>
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

</x-app-layout>
