<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;
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
        $venue = Venue::create($request->validated());

        return redirect()->route('venues.show', $venue)->with('status', 'Venue created.');
    }

    public function show(Venue $venue)
    {
        $this->authorize('modify venues');

        return view('venues.show', [
            'venue' => $venue->load(['rooms', 'products'] )
        ]);
    }

    public function edit(Venue $venue)
    {
        $this->authorize('modify venues');

        return view('venues.create', compact('venue'));
    }

    public function update(CreateVenueRequest $request, Venue $venue)
    {
        $venue->update($request->validated());

        return redirect()->route('venues.show', $venue)->with('status', 'Venue updated.');
    }

    public function destroy(Venue $venue)
    {
        $this->authorize('delete venues');

        $venue->delete();

        return redirect()->route('venues.index')->with('status', 'Venue deleted!');
    }
}
