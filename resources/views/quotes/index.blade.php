<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Quotes</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <form method="GET" class="flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search reference, address, client..." class="border-gray-300 rounded">
                        <select name="status" class="border-gray-300 rounded">
                            <option value="">All statuses</option>
                            @foreach (['draft', 'sent', 'approved', 'rejected'] as $status)
                                <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-3 py-1 bg-gray-800 text-white rounded">Filter</button>
                    </form>

                    @can('create', App\Models\Quote::class)
                        <a href="{{ route('quotes.create') }}" class="px-3 py-1 bg-indigo-600 text-white rounded">+ New Quote</a>
                    @endcan
                </div>

                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Reference</th>
                            <th>Client</th>
                            <th>Site Address</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($quotes as $quote)
                            <tr class="border-b">
                                <td class="py-2">{{ $quote->reference_no }}</td>
                                <td>{{ $quote->client->company_name }}</td>
                                <td>{{ $quote->site_address }}</td>
                                <td>${{ number_format($quote->amount, 2) }}</td>
                                <td><span class="px-2 py-1 text-xs rounded bg-gray-200">{{ ucfirst($quote->status) }}</span></td>
                                <td><a href="{{ route('quotes.show', $quote) }}" class="text-indigo-600">View</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-4 text-gray-500">No quotes found.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $quotes->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
