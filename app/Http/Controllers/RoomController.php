<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateRoomRequest;

class RoomController extends Controller
{
    public function create(Request $request)
    {
        $this->authorize('create rooms');

        return view('rooms.create', [
            'venue_id' => $request->query('venue')
        ]);
      }

    public function store(CreateRoomRequest $request)
    {
        $room = Room::create($request->validated());

        return redirect()->route('venues.show', $room->venue)->with('status', 'Raum wurde angelegt.');
    }

    public function edit(Room $room)
    {
        $this->authorize('modify rooms');

        return view('rooms.create', compact('room'));
    }

    public function update(CreateRoomRequest $request, Room $room)
    {
        $room->update($request->validated());

        return redirect()->route('venues.show', $room->venue_id)->with('status', 'Raum aktualisiert');
    }

    public function destroy(Room $room)
    {
        $this->authorize('delete rooms');

        Storage::delete($room->image);

        $room->packages()->sync([]);
        $room->delete();

        return redirect()->route('venues.show', $room->venue)->with('status', 'Raum wurde gelöscht.');
    }
}
