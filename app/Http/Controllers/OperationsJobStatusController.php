<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateJobStatusRequest;
use App\Models\OperationsJob;

class OperationsJobStatusController extends Controller
{
    public function update(UpdateJobStatusRequest $request, OperationsJob $job)
    {
        $this->authorize('update', $job);

        $job->update($request->validated());

        return response()->json([
            'status' => $job->status,
        ]);
    }
}
