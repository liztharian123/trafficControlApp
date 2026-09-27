<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Quote — {{ $quote->reference_no }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6 space-y-2">
                <p><strong>Client:</strong> {{ $quote->client->company_name }}</p>
                <p><strong>Site Address:</strong> {{ $quote->site_address }}</p>
                <p><strong>Description:</strong> {{ $quote->description }}</p>
                <p><strong>Dates:</strong> {{ $quote->start_date->format('d M Y') }} – {{ $quote->end_date->format('d M Y') }}</p>
                <p><strong>Amount:</strong> ${{ number_format($quote->amount, 2) }}</p>
                <p><strong>Status:</strong> {{ ucfirst($quote->status) }}</p>
                <p><strong>Created by:</strong> {{ $quote->creator->name }}</p>

                <div class="pt-4 flex gap-3">
                    @can('update', $quote)
                        <a href="{{ route('quotes.edit', $quote) }}" class="px-3 py-1 bg-gray-800 text-white rounded">Edit</a>
                    @endcan

                    @can('delete', $quote)
                        <form method="POST" action="{{ route('quotes.destroy', $quote) }}" onsubmit="return confirm('Delete this quote?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">Delete</button>
                        </form>
                    @endcan

                    <a href="{{ route('quotes.index') }}" class="px-3 py-1 border rounded">Back</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
