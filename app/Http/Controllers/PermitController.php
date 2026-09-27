<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermitRequest;
use App\Http\Requests\UpdatePermitRequest;
use App\Models\Permit;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PermitController extends Controller
{
    public function index(Request $request)
    {
        $permits = Permit::with('quote.client')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('permits.index', compact('permits'));
    }

    public function create()
    {
        $this->authorize('create', Permit::class);

        $quotes = Quote::awaitingPermit()->with('client')->get();

        return view('permits.create', compact('quotes'));
    }

    public function store(StorePermitRequest $request)
    {
        $this->authorize('create', Permit::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        if ($request->hasFile('document')) {
            $data['document_path'] = $request->file('document')->store('permits', 'public');
        }

        $permit = Permit::create($data);

        return redirect()->route('permits.show', $permit)->with('status', 'Permit created.');
    }

    public function show(Permit $permit)
    {
        $this->authorize('view', $permit);

        $permit->load('quote.client', 'creator');

        return view('permits.show', compact('permit'));
    }

    public function edit(Permit $permit)
    {
        $this->authorize('update', $permit);

        return view('permits.edit', compact('permit'));
    }

    public function update(UpdatePermitRequest $request, Permit $permit)
    {
        $this->authorize('update', $permit);

        $data = $request->validated();

        if ($request->hasFile('document')) {
            if ($permit->document_path) {
                Storage::disk('public')->delete($permit->document_path);
            }
            $data['document_path'] = $request->file('document')->store('permits', 'public');
        }

        $permit->update($data);

        return redirect()->route('permits.show', $permit)->with('status', 'Permit updated.');
    }

    public function destroy(Permit $permit)
    {
        $this->authorize('delete', $permit);

        if ($permit->document_path) {
            Storage::disk('public')->delete($permit->document_path);
        }

        $permit->delete();

        return redirect()->route('permits.index')->with('status', 'Permit deleted.');
    }

    public function download(Permit $permit)
    {
        $this->authorize('view', $permit);

        abort_unless($permit->document_path, 404);

        return Storage::disk('public')->download($permit->document_path);
    }
}
