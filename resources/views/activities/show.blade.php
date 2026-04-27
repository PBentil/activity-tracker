<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $activity->title }}
            </h2>
            <a href="{{ route('activities.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Activity Details --}}
            <div class="bg-white rounded shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Activity Details</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Category</p>
                        <p class="font-medium">{{ ucfirst($activity->category) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Date</p>
                        <p class="font-medium">{{ $activity->activity_date->format('F j, Y') }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-gray-500">Description</p>
                        <p class="font-medium">{{ $activity->description ?? 'No description provided.' }}</p>
                    </div>
                </div>
            </div>

            {{-- Update Status Form --}}
            <div class="bg-white rounded shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Update Activity Status</h3>
                <form method="POST" action="{{ route('activity-updates.store', $activity) }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status"
                                class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="pending">Pending</option>
                            <option value="done">Done</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Remark</label>
                        <textarea name="remark" rows="3"
                                  class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Add a remark about this update..."></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Submit Update
                        </button>
                    </div>
                </form>
            </div>

            {{-- Update History --}}
            <div class="bg-white rounded shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Update History</h3>
                @if($activity->updates->isEmpty())
                    <p class="text-gray-500 text-sm">No updates yet.</p>
                @else
                    <div class="space-y-4">
                        @foreach($activity->updates->sortByDesc('updated_at_time') as $update)
                            <div class="border rounded p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $update->user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $update->user->email }}</p>
                                        <p class="text-gray-600 mt-1">{{ $update->remark ?? 'No remark' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-3 py-1 rounded-full text-sm font-medium
                                            {{ $update->status === 'done'
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($update->status) }}
                                        </span>
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ $update->updated_at_time->format('F j, Y h:i A') }}
                                        </p>
                                    </div>
                                </div>
                                <form method="POST"
                                      action="{{ route('activity-updates.destroy', $update) }}"
                                      class="mt-2 text-right">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-500 hover:underline text-xs"
                                            onclick="return confirm('Delete this update?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
