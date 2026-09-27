<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Job — {{ $job->permit->quote->reference_no }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('jobs.update', $job) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Permit</label>
                        <p class="mt-1 text-gray-600">{{ $job->permit->quote->reference_no }} — {{ $job->permit->quote->client->company_name }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Site Address</label>
                        <input type="text" name="site_address" value="{{ old('site_address', $job->site_address) }}" class="mt-1 block w-full border-gray-300 rounded">
                        @error('site_address') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        <p class="text-xs text-gray-500 mt-1">Changing this re-runs the geocoder and moves the map pin.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Scheduled Date</label>
                        <input type="date" name="scheduled_date" value="{{ old('scheduled_date', $job->scheduled_date->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded">
                        @error('scheduled_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Shift Start</label>
                            <input type="time" name="shift_start" value="{{ old('shift_start', substr($job->shift_start, 0, 5)) }}" class="mt-1 block w-full border-gray-300 rounded">
                            @error('shift_start') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Shift End</label>
                            <input type="time" name="shift_end" value="{{ old('shift_end', substr($job->shift_end, 0, 5)) }}" class="mt-1 block w-full border-gray-300 rounded">
                            @error('shift_end') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" class="mt-1 block w-full border-gray-300 rounded">{{ old('notes', $job->notes) }}</textarea>
                        @error('notes') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
