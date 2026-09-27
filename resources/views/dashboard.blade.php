<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (! empty($unassigned))
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 mb-2">No department assigned</h3>
                    <p class="text-sm text-gray-600">Your account isn't linked to a department yet, so no modules are available. Please ask an administrator to assign you to Quote, Permit or Operations.</p>
                </div>
            @elseif (isset($pipeline))
                {{-- Admin: cross-department pipeline view --}}
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Pipeline — Quote → Permit → Job</h3>
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="py-2">Reference</th>
                                <th>Client</th>
                                <th>Quote Status</th>
                                <th>Permit Status</th>
                                <th>Job Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pipeline as $quote)
                                <tr class="border-b">
                                    <td class="py-2"><a href="{{ route('quotes.show', $quote) }}" class="text-indigo-600">{{ $quote->reference_no }}</a></td>
                                    <td>{{ $quote->client->company_name }}</td>
                                    <td><span class="px-2 py-1 text-xs rounded bg-gray-200">{{ ucfirst($quote->status) }}</span></td>
                                    <td>
                                        @if ($quote->permit)
                                            <span class="px-2 py-1 text-xs rounded bg-gray-200">{{ ucfirst($quote->permit->status) }}</span>
                                        @else
                                            <span class="text-gray-400 text-xs">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($quote->permit?->job)
                                            <span class="px-2 py-1 text-xs rounded bg-gray-200">{{ ucfirst(str_replace('_', ' ', $quote->permit->job->status)) }}</span>
                                        @else
                                            <span class="text-gray-400 text-xs">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $pipeline->links() }}</div>
                </div>
            @else
                {{-- Department dashboard: stats + recent records --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach ($stats as $label => $count)
                        <div class="bg-white shadow-sm rounded-lg p-4">
                            <p class="text-2xl font-bold text-gray-800">{{ $count }}</p>
                            <p class="text-xs text-gray-500 uppercase">{{ str_replace('_', ' ', $label) }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Recent</h3>
                    <ul class="space-y-2 text-sm">
                        @forelse ($recent as $item)
                            <li class="border-b pb-2">
                                @if ($module === 'quote')
                                    <a href="{{ route('quotes.show', $item) }}" class="text-indigo-600">{{ $item->reference_no }}</a> — {{ $item->client->company_name }}
                                @elseif ($module === 'permit')
                                    <a href="{{ route('permits.show', $item) }}" class="text-indigo-600">{{ $item->quote->reference_no }}</a> — {{ $item->authority }}
                                @else
                                    <a href="{{ route('jobs.show', $item) }}" class="text-indigo-600">{{ $item->permit->quote->reference_no }}</a> — {{ $item->scheduled_date->format('d M Y') }}
                                @endif
                            </li>
                        @empty
                            <li class="text-gray-500">Nothing yet.</li>
                        @endforelse
                    </ul>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
