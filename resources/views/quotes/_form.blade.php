@csrf

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Client</label>
    <select name="client_id" class="mt-1 block w-full border-gray-300 rounded">
        @foreach ($clients as $client)
            <option value="{{ $client->id }}" @selected(old('client_id', $quote->client_id ?? null) == $client->id)>
                {{ $client->company_name }}
            </option>
        @endforeach
    </select>
    @error('client_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Reference No.</label>
    <input type="text" name="reference_no" value="{{ old('reference_no', $quote->reference_no ?? '') }}" class="mt-1 block w-full border-gray-300 rounded">
    @error('reference_no') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Site Address</label>
    <input type="text" name="site_address" value="{{ old('site_address', $quote->site_address ?? '') }}" class="mt-1 block w-full border-gray-300 rounded">
    @error('site_address') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Description</label>
    <textarea name="description" class="mt-1 block w-full border-gray-300 rounded">{{ old('description', $quote->description ?? '') }}</textarea>
    @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="mb-4 grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Start Date</label>
        <input type="date" name="start_date" value="{{ old('start_date', isset($quote->start_date) ? $quote->start_date->format('Y-m-d') : '') }}" class="mt-1 block w-full border-gray-300 rounded">
        @error('start_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">End Date</label>
        <input type="date" name="end_date" value="{{ old('end_date', isset($quote->end_date) ? $quote->end_date->format('Y-m-d') : '') }}" class="mt-1 block w-full border-gray-300 rounded">
        @error('end_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Amount ($)</label>
    <input type="number" step="0.01" name="amount" value="{{ old('amount', $quote->amount ?? '') }}" class="mt-1 block w-full border-gray-300 rounded">
    @error('amount') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Status</label>
    <select name="status" class="mt-1 block w-full border-gray-300 rounded">
        @foreach (['draft', 'sent', 'approved', 'rejected'] as $status)
            <option value="{{ $status }}" @selected(old('status', $quote->status ?? 'draft') === $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
    @error('status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
