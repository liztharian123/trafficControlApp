<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Models\Client;
use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $quotes = Quote::filter($request->search, $request->status)
            ->with('client')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('quotes.index', compact('quotes'));
    }

    public function create()
    {
        $this->authorize('create', Quote::class);

        $clients = Client::orderBy('company_name')->get();

        return view('quotes.create', compact('clients'));
    }

    public function store(StoreQuoteRequest $request)
    {
        $this->authorize('create', Quote::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $quote = Quote::create($data);

        return redirect()->route('quotes.show', $quote)->with('status', 'Quote created.');
    }

    public function show(Quote $quote)
    {
        $this->authorize('view', $quote);

        $quote->load('client', 'creator', 'permit');

        return view('quotes.show', compact('quote'));
    }

    public function edit(Quote $quote)
    {
        $this->authorize('update', $quote);

        $clients = Client::orderBy('company_name')->get();

        return view('quotes.edit', compact('quote', 'clients'));
    }

    public function update(UpdateQuoteRequest $request, Quote $quote)
    {
        $this->authorize('update', $quote);

        $quote->update($request->validated());

        return redirect()->route('quotes.show', $quote)->with('status', 'Quote updated.');
    }

    public function destroy(Quote $quote)
    {
        $this->authorize('delete', $quote);

        $quote->delete();

        return redirect()->route('quotes.index')->with('status', 'Quote deleted.');
    }
}
