<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Activity Reports
            </h2>
            <a href="{{ route('activities.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter by Date Range</h3>
                <form method="GET" action="{{ route('activities.report') }}">
                    <div class="flex gap-4 items-end">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
                            <input type="date" name="from" value="{{ $from }}"
                                   class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                            <input type="date" name="to" value="{{ $to }}"
                                   class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <button type="submit"
                                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                                Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    Results: {{ $activities->count() }} activities
                    from {{ \Carbon\Carbon::parse($from)->format('F j, Y') }}
                    to {{ \Carbon\Carbon::parse($to)->format('F j, Y') }}
                </h3>

                @if($activities->isEmpty())
                    <p class="text-gray-500 text-sm">No activities found for this period.</p>
                @else
                    <div class="space-y-6">
                        @foreach($activities as $activity)
                            <div class="border rounded p-4">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h4 class="font-semibold text-gray-800">{{ $activity->title }}</h4>
                                        <p class="text-sm text-gray-500">
                                            {{ ucfirst($activity->category) }} —
                                            {{ $activity->activity_date->format('F j, Y') }}
                                        </p>
                                        <p class="text-gray-600 text-sm mt-1">{{ $activity->description }}</p>
                                    </div>
                                    @if($activity->latestUpdate)
                                        <span class="px-3 py-1 rounded-full text-sm font-medium
                                            {{ $activity->latestUpdate->status === 'done'
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($activity->latestUpdate->status) }}
                                        </span>
                                    @endif
                                </div>

                                @if($activity->updates->isNotEmpty())
                                    <div class="border-t pt-3">
                                        <p class="text-sm font-medium text-gray-700 mb-2">Updates:</p>
                                        <div class="space-y-2">
                                            @foreach($activity->updates->sortByDesc('updated_at_time') as $update)
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-600">
                                                        <span class="font-medium">{{ $update->user->name }}</span>
                                                        — {{ $update->remark ?? 'No remark' }}
                                                    </span>
                                                    <span class="text-gray-400">
                                                        {{ $update->updated_at_time->format('F j, Y h:i A') }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
