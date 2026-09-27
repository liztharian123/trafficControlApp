<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCrewRequest;
use App\Models\OperationsJob;
use App\Models\User;

class OperationsJobCrewController extends Controller
{
    public function store(StoreCrewRequest $request, OperationsJob $job)
    {
        $this->authorize('update', $job);

        $data = $request->validated();

        $job->crew()->syncWithoutDetaching([
            $data['user_id'] => ['site_role' => $data['site_role']],
        ]);

        return redirect()->route('jobs.show', $job)->with('status', 'Crew member added.');
    }

    public function destroy(OperationsJob $job, User $user)
    {
        $this->authorize('update', $job);

        $job->crew()->detach($user->id);

        return redirect()->route('jobs.show', $job)->with('status', 'Crew member removed.');
    }
}
