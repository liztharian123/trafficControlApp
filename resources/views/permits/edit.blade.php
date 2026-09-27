<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Permit — {{ $permit->quote->reference_no }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('permits.update', $permit) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Quote</label>
                        <p class="mt-1 text-gray-600">{{ $permit->quote->reference_no }} — {{ $permit->quote->client->company_name }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Authority</label>
                        <input type="text" name="authority" value="{{ old('authority', $permit->authority) }}" class="mt-1 block w-full border-gray-300 rounded">
                        @error('authority') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Permit Number</label>
                        <input type="text" name="permit_number" value="{{ old('permit_number', $permit->permit_number) }}" class="mt-1 block w-full border-gray-300 rounded">
                        @error('permit_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lodged Date</label>
                            <input type="date" name="lodged_date" value="{{ old('lodged_date', $permit->lodged_date->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded">
                            @error('lodged_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                            <input type="date" name="expiry_date" value="{{ old('expiry_date', optional($permit->expiry_date)->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded">
                            @error('expiry_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="mt-1 block w-full border-gray-300 rounded">
                            @foreach (['pending', 'lodged', 'approved', 'rejected'] as $status)
                                <option value="{{ $status }}" @selected(old('status', $permit->status) === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                        @error('status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Replace Document (optional)</label>
                        <input type="file" name="document" class="mt-1 block w-full">
                        @error('document') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" class="mt-1 block w-full border-gray-300 rounded">{{ old('notes', $permit->notes) }}</textarea>
                        @error('notes') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
