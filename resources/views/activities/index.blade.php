<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Daily Activities — {{ today()->format('F j, Y') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('activities.report') }}"
                   class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Reports
                </a>
                <a href="{{ route('activities.create') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + New Activity
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($activities->isEmpty())
                <div class="bg-white rounded shadow p-6 text-center text-gray-500">
                    No activities recorded for today. Click "+ New Activity" to add one.
                </div>
            @else
                <div class="space-y-4">
                    @foreach($activities as $activity)
                        <div class="bg-white rounded shadow p-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">
                                        {{ $activity->title }}
                                    </h3>
                                    <p class="text-sm text-gray-500">{{ $activity->category }}</p>
                                    <p class="text-gray-600 mt-1">{{ $activity->description }}</p>
                                </div>
                                <div class="text-right">
                                    @if($activity->latestUpdate)
                                        <span class="px-3 py-1 rounded-full text-sm font-medium
                                            {{ $activity->latestUpdate->status === 'done'
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($activity->latestUpdate->status) }}
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">
                                            No Update
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if($activity->updates->isNotEmpty())
                                <div class="mt-4 border-t pt-4">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Updates:</p>
                                    <div class="space-y-2">
                                        @foreach($activity->updates as $update)
                                            <div class="text-sm text-gray-600 flex justify-between">
                                                <span>
                                                    <span class="font-medium">{{ $update->user->name }}</span>
                                                    — {{ $update->remark ?? 'No remark' }}
                                                </span>
                                                <span class="text-gray-400">
                                                    {{ $update->updated_at_time->format('h:i A') }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="mt-4">
                                <a href="{{ route('activities.show', $activity) }}"
                                   class="text-blue-600 hover:underline text-sm">
                                    View & Update →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
