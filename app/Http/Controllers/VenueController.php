<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;
use App\Http\Requests\CreateVenueRequest;

class VenueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('venues.index', [
            'venues' => Venue::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $this->authorize('create venues');

      return view('venues.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\CreateVenueRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateVenueRequest $request)
    {
        $venue = Venue::create($request->validated());

        return redirect()->route('venues.show', $venue)->with('status', 'Venue created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function show(Venue $venue)
    {
        return view('venues.show', [
            'venue' => $venue->load(['rooms', 'products'] )
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function edit(Venue $venue)
    {
        $this->authorize('modify venues');

        return view('venues.create', compact('venue'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\CreateVenueRequest  $request
     * @param  \App\Models\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function update(CreateVenueRequest $request, Venue $venue)
    {
        $venue->update($request->validated());

        return redirect()->route('venues.show', $venue)->with('status', 'Venue updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venue $venue)
    {
        $this->authorize('delete venues');

        $venue->delete();

        return redirect()->route('venues.index')->with('status', 'Venue deleted!');
    }
}
