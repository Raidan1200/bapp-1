<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(Request $request) {
        $venues = Cache::has('venues')
            ? Cache::get('venues')
            : $this->cacheVenues();

        $venue = $request->input('venue');
        $room = $request->input('room');

        if (!$venue && !$room) {
            $from = $request->input('from') ? new Carbon($request->input('from')) : Carbon::today();
            $to   = $request->input('to')   ? new Carbon($request->input('to'))   : new Carbon($from);
        }

        $query = Order::with('customer')
            ->with('bookings')
            ->with('bookings.product') // TODO: remove when product snapshots are in place
            ->with('bookings.room');

        if (!$venue && !$room) {
            $query->whereExists(function ($q) use ($from, $to) {
                $q->select(DB::raw(1))
                    ->from('bookings')
                    ->whereColumn('bookings.order_id', 'orders.id')
                    ->whereBetween('starts_at', [$from, (new Carbon($to))->hour(23)->minute(59)->second(59)]);
                });
        }

        if ($room) {
            $query->whereExists(function ($q) use ($room) {
                $q->select(DB::raw(1))
                    ->from('bookings')
                    ->whereColumn('bookings.order_id', 'orders.id')
                    ->where('room_id', $room);
                });
        }

        if ($venue) {
            $query->where('venue_id', $venue);
        }

        $orders = $query->get();

        $view_data = compact('venues', 'orders');

        if (isset($from)) {
            $interval = $request->input('interval');
            $view_data = array_merge($view_data, compact('from', 'to', 'interval'));
        }

        return view('dashboard.index', $view_data);
    }

    protected function cacheVenues() {
        $venues = Venue::with(['rooms' => function ($q) {
            $q->orderBy('name')->with(['products' => function ($r) {
                $r->orderBy('name');
            }]);
        }])
        ->orderBy('name')
        ->get();
;
        Cache::put('venues', $venues);

        return $venues;
    }
}
