<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Jobs</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6">
                <div id="jobs-map" class="h-96 rounded"></div>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="flex justify-end mb-4">
                    @can('create', App\Models\OperationsJob::class)
                        <a href="{{ route('jobs.create') }}" class="px-3 py-1 bg-indigo-600 text-white rounded">+ Schedule Job</a>
                    @endcan
                </div>

                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Client</th>
                            <th>Site Address</th>
                            <th>Scheduled</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jobs as $job)
                            <tr class="border-b">
                                <td class="py-2">{{ $job->permit->quote->client->company_name }}</td>
                                <td>{{ $job->site_address }}</td>
                                <td>{{ $job->scheduled_date->format('d M Y') }}</td>
                                <td><span class="px-2 py-1 text-xs rounded bg-gray-200">{{ ucfirst($job->status) }}</span></td>
                                <td><a href="{{ route('jobs.show', $job) }}" class="text-indigo-600">View</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-4 text-gray-500">No jobs found.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $jobs->links() }}</div>
            </div>
        </div>
    </div>

    @push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>
    @endpush

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const pins = @json($pins);
        const map = L.map('jobs-map').setView([-28.0, 153.4], 10);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        pins.forEach(p => L.marker([p.lat, p.lng]).addTo(map).bindPopup(`<a href="${p.url}">${p.label}</a>`));
    });
    </script>
    @endpush
</x-app-layout>
