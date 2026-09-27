<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Job — {{ $job->permit->quote->reference_no }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6 space-y-2">
                <p><strong>Client:</strong> {{ $job->permit->quote->client->company_name }}</p>
                <p><strong>Site Address:</strong> {{ $job->site_address }}</p>
                <p><strong>Scheduled:</strong> {{ $job->scheduled_date->format('d M Y') }}, {{ substr($job->shift_start, 0, 5) }}–{{ substr($job->shift_end, 0, 5) }}</p>
                <p>
                    <strong>Status:</strong>
                    @can('update', $job)
                        <select id="job-status-select" class="text-xs border-gray-300 rounded" data-url="{{ route('jobs.status', $job) }}">
                            @foreach (['scheduled', 'in_progress', 'completed', 'cancelled'] as $status)
                                <option value="{{ $status }}" @selected($job->status === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                            @endforeach
                        </select>
                        <span id="job-status-message" class="text-xs text-gray-500 ml-2"></span>
                    @else
                        <span class="px-2 py-1 text-xs rounded bg-gray-200">{{ ucfirst(str_replace('_', ' ', $job->status)) }}</span>
                    @endcan
                </p>
                <p><strong>Created by:</strong> {{ $job->creator->name }}</p>

                @if ($job->hasCoordinates())
                    <div id="map" class="h-80 w-full rounded border mt-4"
                         data-lat="{{ $job->latitude }}"
                         data-lng="{{ $job->longitude }}"
                         data-label="{{ $job->permit->quote->client->company_name }} — {{ $job->scheduled_date->format('d M Y') }}">
                    </div>
                    <p class="text-sm text-gray-600 mt-2">
                        Located as: {{ $job->formatted_address }}
                        (geocoded {{ $job->geocoded_at->diffForHumans() }})
                    </p>
                @else
                    <p class="text-amber-700 mt-4">Location not available — the address could not be geocoded.</p>
                @endif

                <div class="pt-4">
                    <h3 class="font-semibold text-gray-800 mb-2">Crew</h3>

                    <ul class="mb-3 space-y-1">
                        @forelse ($job->crew as $member)
                            <li class="flex justify-between items-center text-sm bg-gray-50 px-3 py-2 rounded">
                                <span>{{ $member->name }} — <span class="text-gray-500">{{ ucfirst($member->pivot->site_role) }}</span></span>
                                @can('update', $job)
                                    <form method="POST" action="{{ route('jobs.crew.destroy', [$job, $member]) }}" onsubmit="return confirm('Remove {{ $member->name }} from this job?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 text-xs">Remove</button>
                                    </form>
                                @endcan
                            </li>
                        @empty
                            <li class="text-sm text-gray-500">No crew assigned yet.</li>
                        @endforelse
                    </ul>

                    @can('update', $job)
                        <form method="POST" action="{{ route('jobs.crew.store', $job) }}" class="flex gap-2 items-end">
                            @csrf
                            <div>
                                <label class="block text-xs text-gray-600">Add crew member</label>
                                <select name="user_id" class="border-gray-300 rounded text-sm">
                                    @foreach ($opsUsers->whereNotIn('id', $job->crew->pluck('id')) as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600">Role</label>
                                <select name="site_role" class="border-gray-300 rounded text-sm">
                                    <option value="controller">Controller</option>
                                    <option value="supervisor">Supervisor</option>
                                </select>
                            </div>
                            <button type="submit" class="px-3 py-1 bg-indigo-600 text-white rounded text-sm">Add</button>
                        </form>
                    @endcan
                </div>

                <div class="pt-4 flex gap-3">
                    @can('update', $job)
                        <a href="{{ route('jobs.edit', $job) }}" class="px-3 py-1 bg-gray-800 text-white rounded">Edit</a>
                    @endcan

                    @can('delete', $job)
                        <form method="POST" action="{{ route('jobs.destroy', $job) }}" onsubmit="return confirm('Delete this job?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">Delete</button>
                        </form>
                    @endcan

                    <a href="{{ route('jobs.index') }}" class="px-3 py-1 border rounded">Back</a>
                </div>
            </div>
        </div>
    </div>

    @push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>
    @endpush

    @if ($job->hasCoordinates())
        @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', () => {
            const el  = document.getElementById('map');
            const lat = parseFloat(el.dataset.lat);
            const lng = parseFloat(el.dataset.lng);

            const map = L.map(el).setView([lat, lng], 15);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            L.marker([lat, lng]).addTo(map).bindPopup(el.dataset.label).openPopup();
        });
        </script>
        @endpush
    @endif

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const select = document.getElementById('job-status-select');
        if (! select) return;

        const message = document.getElementById('job-status-message');

        select.addEventListener('change', () => {
            fetch(select.dataset.url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ status: select.value }),
            })
            .then(response => {
                if (! response.ok) throw new Error('Update failed');
                return response.json();
            })
            .then(() => {
                message.textContent = 'Updated.';
                setTimeout(() => message.textContent = '', 2000);
            })
            .catch(() => {
                message.textContent = 'Failed to update status.';
            });
        });
    });
    </script>
    @endpush
</x-app-layout>
