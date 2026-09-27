<?php

namespace App\Http\Controllers;

use App\Models\OperationsJob;
use App\Models\Permit;
use App\Models\Quote;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $pipeline = Quote::with('client', 'permit.job')->latest()->paginate(15);

            return view('dashboard', ['pipeline' => $pipeline]);
        }

        if (! $user->department) {
            return view('dashboard', ['unassigned' => true]);
        }

        if ($user->inDepartment('quote')) {
            $stats = [
                'draft'    => Quote::where('status', 'draft')->count(),
                'sent'     => Quote::where('status', 'sent')->count(),
                'approved' => Quote::where('status', 'approved')->count(),
                'rejected' => Quote::where('status', 'rejected')->count(),
            ];
            $recent = Quote::with('client')->latest()->take(5)->get();

            return view('dashboard', ['stats' => $stats, 'recent' => $recent, 'module' => 'quote']);
        }

        if ($user->inDepartment('permit')) {
            $stats = [
                'awaiting_permit' => Quote::awaitingPermit()->count(),
                'pending'         => Permit::where('status', 'pending')->count(),
                'lodged'          => Permit::where('status', 'lodged')->count(),
                'approved'        => Permit::where('status', 'approved')->count(),
            ];
            $recent = Permit::with('quote.client')->latest()->take(5)->get();

            return view('dashboard', ['stats' => $stats, 'recent' => $recent, 'module' => 'permit']);
        }

        // Operations
        $stats = [
            'ready_to_schedule' => Permit::readyToSchedule()->count(),
            'scheduled'         => OperationsJob::where('status', 'scheduled')->count(),
            'in_progress'       => OperationsJob::where('status', 'in_progress')->count(),
            'completed'         => OperationsJob::where('status', 'completed')->count(),
        ];
        $recent = OperationsJob::with('permit.quote.client')->latest()->take(5)->get();

        return view('dashboard', ['stats' => $stats, 'recent' => $recent, 'module' => 'operations']);
    }
}
