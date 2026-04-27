<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Add New Activity
            </h2>
            <a href="{{ route('activities.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded shadow p-6">
                @if($errors->any())
                    <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('activities.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Activity Title
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="e.g. Daily SMS count in comparison to SMS count from logs">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Category
                        </label>
                        <select name="category"
                                class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>General</option>
                            <option value="sms" {{ old('category') == 'sms' ? 'selected' : '' }}>SMS</option>
                            <option value="logs" {{ old('category') == 'logs' ? 'selected' : '' }}>Logs</option>
                            <option value="monitoring" {{ old('category') == 'monitoring' ? 'selected' : '' }}>Monitoring</option>
                            <option value="incident" {{ old('category') == 'incident' ? 'selected' : '' }}>Incident</option>
                            <option value="change" {{ old('category') == 'change' ? 'selected' : '' }}>Change Management</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <textarea name="description" rows="4"
                                  class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Describe the activity in detail...">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Activity Date
                        </label>
                        <input type="date" name="activity_date"
                               value="{{ old('activity_date', today()->toDateString()) }}"
                               class="w-full border-gray-300 rounded shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Save Activity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
