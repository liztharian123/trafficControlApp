<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOperationsJobRequest;
use App\Http\Requests\UpdateOperationsJobRequest;
use App\Models\OperationsJob;
use App\Models\Permit;
use App\Models\User;
use App\Services\GeocodingService;
use Illuminate\Http\Request;

class OperationsJobController extends Controller
{
    public function index(Request $request)
    {
        $jobs = OperationsJob::with('permit.quote.client')
            ->orderBy('scheduled_date')
            ->paginate(10)
            ->withQueryString();

        $pins = OperationsJob::whereNotNull('latitude')
            ->with('permit.quote.client')
            ->get()
            ->map(fn ($job) => [
                'lat'   => (float) $job->latitude,
                'lng'   => (float) $job->longitude,
                'label' => $job->permit->quote->client->company_name,
                'url'   => route('jobs.show', $job),
            ]);

        return view('jobs.index', compact('jobs', 'pins'));
    }

    public function create()
    {
        $this->authorize('create', OperationsJob::class);

        $permits = Permit::readyToSchedule()->with('quote.client')->get();

        return view('jobs.create', compact('permits'));
    }

    public function store(StoreOperationsJobRequest $request, GeocodingService $geocoder)
    {
        $this->authorize('create', OperationsJob::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $geo = $geocoder->geocode($data['site_address']);

        if ($geo) {
            $data['latitude']          = $geo['lat'];
            $data['longitude']         = $geo['lng'];
            $data['formatted_address'] = $geo['formatted'];
            $data['geocoded_at']       = now();
        }

        $job = OperationsJob::create($data);

        return redirect()->route('jobs.show', $job)
            ->with('status', $geo ? 'Job scheduled and pinned on the map.' : 'Job scheduled, but the address could not be located.');
    }

    public function show(OperationsJob $job)
    {
        $this->authorize('view', $job);

        $job->load('permit.quote.client', 'creator', 'crew');

        $opsUsers = User::whereRelation('department', 'slug', 'operations')->get();

        return view('jobs.show', compact('job', 'opsUsers'));
    }

    public function edit(OperationsJob $job)
    {
        $this->authorize('update', $job);

        return view('jobs.edit', compact('job'));
    }

    public function update(UpdateOperationsJobRequest $request, OperationsJob $job, GeocodingService $geocoder)
    {
        $this->authorize('update', $job);

        $data = $request->validated();

        if ($data['site_address'] !== $job->site_address) {
            $geo = $geocoder->geocode($data['site_address']);

            $data['latitude']          = $geo['lat'] ?? null;
            $data['longitude']         = $geo['lng'] ?? null;
            $data['formatted_address'] = $geo['formatted'] ?? null;
            $data['geocoded_at']       = $geo ? now() : null;
        }

        $job->update($data);

        return redirect()->route('jobs.show', $job)->with('status', 'Job updated.');
    }

    public function destroy(OperationsJob $job)
    {
        $this->authorize('delete', $job);

        $job->delete();

        return redirect()->route('jobs.index')->with('status', 'Job deleted.');
    }
}
