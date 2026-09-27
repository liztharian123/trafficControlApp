<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Permit — {{ $permit->quote->reference_no }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6 space-y-2">
                <p><strong>Quote:</strong> {{ $permit->quote->reference_no }} — {{ $permit->quote->client->company_name }}</p>
                <p><strong>Authority:</strong> {{ $permit->authority }}</p>
                <p><strong>Permit Number:</strong> {{ $permit->permit_number ?? '—' }}</p>
                <p><strong>Lodged:</strong> {{ $permit->lodged_date->format('d M Y') }}</p>
                <p><strong>Expiry:</strong> {{ optional($permit->expiry_date)->format('d M Y') ?? '—' }}</p>
                <p><strong>Status:</strong> {{ ucfirst($permit->status) }}</p>
                <p><strong>Created by:</strong> {{ $permit->creator->name }}</p>

                @if ($permit->document_path)
                    <p><a href="{{ route('permits.document', $permit) }}" class="text-indigo-600">Download document</a></p>
                @endif

                <div class="pt-4 flex gap-3">
                    @can('update', $permit)
                        <a href="{{ route('permits.edit', $permit) }}" class="px-3 py-1 bg-gray-800 text-white rounded">Edit</a>
                    @endcan

                    @can('delete', $permit)
                        <form method="POST" action="{{ route('permits.destroy', $permit) }}" onsubmit="return confirm('Delete this permit?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">Delete</button>
                        </form>
                    @endcan

                    <a href="{{ route('permits.index') }}" class="px-3 py-1 border rounded">Back</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
