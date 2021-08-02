<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use App\Http\Requests\CreateRoomRequest;

class RoomController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create rooms');

        return view('rooms.create', [
            'venue_id' => $request->query('venue')
        ]);
      }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\CreateRoomRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRoomRequest $request)
    {
        $room = Room::create($request->validated());

        return redirect()->route('venues.show', $room->venue)->with('status', 'Raum wurde angelegt.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        $this->authorize('modify rooms');

        return view('rooms.create', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(CreateRoomRequest $request, Room $room)
    {
        $room->update($request->validated());

        return redirect()->route('rooms.edit', $room->venue_id)->with('status', 'Raum aktualisiert');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        $this->authorize('delete rooms');

        Storage::delete($room->image);

        $room->products()->sync([]);
        $room->delete();

        return redirect()->route('venues.show', $room->venue)->with('status', 'Raum wurde gelöscht.');
    }
}
