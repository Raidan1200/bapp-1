<?php

namespace App\Filters;

use Illuminate\Support\Carbon;

class OrderFilters extends QueryFilter
{
    public function venue(int $venue_id)
    {
        return $this->builder->where('venue_id', $venue_id);
    }

    public function room(int $room_id)
    {
        return $this->builder->whereHas('bookings', fn($q) => $q->where('room_id', $room_id));
    }

    public function state(string $state)
    {
        return $this->builder->where('state', $state);
    }

    public function from(string $from = null)
    {
        $from = $from === null ? now() : $from;
        $days = $this->request->input('days') ?? 7;

        return $this->builder->whereBetween('starts_at', [
            (new Carbon($from))->shiftTimezone('Europe/Berlin')->timezone('UTC'),
            (new Carbon($from))->addDays($days),
        ]);
    }

    public function check()
    {
        return $this->builder->where('needs_check', true);
    }
}
