<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    protected $rules = [
        'name' => 'required',
        'email' => 'sometimes|email',
        'reminder_delay' => 'sometimes|integer',
        'check_delay' => 'sometimes|integer|gte:reminder_delay',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // TODO: Add permission to view venues?

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create venues');

        $validated = $request->validate($this->rules);

        Venue::create($validated);

        Cache::forget('venues');

        return redirect()->route('venues.index')->with('status', 'Venue created.');
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
            'venue' => $venue->load('products')
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Venue  $venue
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Venue $venue)
    {
        $this->authorize('modify venues');

        $validated = $request->validate($this->rules);

        $venue->update($validated);

        Cache::forget('venues');

        return redirect()->route('venues.index')->with('status', 'Venue updated.');

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

        Cache::forget('venues');

        return redirect()->route('venues.index')->with('status', 'Venue deleted!');
    }
}
