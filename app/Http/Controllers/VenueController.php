<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Http\Requests\CreateVenueRequest;

class VenueController extends Controller
{
    public function index()
    {
        $this->authorize('modify venues');

        return view('venues.index', [
            'venues' => Venue::all()
        ]);
    }

    public function create()
    {
      $this->authorize('create venues');

      return view('venues.create');
    }

    public function store(CreateVenueRequest $request)
    {
        $validated = $request->validated();
        $validated['invoice_blocks'] = str_replace(["\r", "\n"], '', $validated['invoice_blocks']);

        $venue = Venue::create($validated);

        return redirect()
            ->route('venues.show', $venue)
            ->with('status', 'Venue created.');
    }

    public function show(Venue $venue)
    {
        $this->authorize('modify venues');

        return view('venues.show', [
            'venue' => $venue->load(['rooms', 'packages', 'products', 'users'])
        ]);
    }

    public function edit(Venue $venue)
    {
        $this->authorize('modify venues');

        return view('venues.create', compact('venue'));
    }

    public function update(CreateVenueRequest $request, Venue $venue)
    {
        $validated = $request->validated();
        $validated['invoice_blocks'] = json_decode($validated['invoice_blocks']);

        $venue->update($validated);

        return redirect()
            ->route('venues.show', $venue)
            ->with('status', 'Veranstaltungsort aktualisiert.');
    }

    public function destroy(Venue $venue)
    {
        $this->authorize('delete venues');

        $venue->delete();

        return redirect()
            ->route('venues.index')
            ->with('status', 'Veranstaltungsort gelöscht!');
    }
}
