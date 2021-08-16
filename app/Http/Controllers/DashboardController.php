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
    public function index(Request $request)
    {
        // Get Rooms and Products of Auth-User for the Sidebar
        $venues = auth()->user()->venues()->with(['rooms' => function ($q) {
            $q->orderBy('name')->with(['products' => function ($r) {
                $r->orderBy('name');
            }]);
        }])->get();

        $venue = $request->input('venue');
        $room = $request->input('room');
        $state = $request->input('state');

        // Get Orders available to Auth-User
        $orders = Order::with(['bookings', 'customer', 'actions'])
            ->orderBy('starts_at')
            ->whereIn('venue_id', $venues->pluck('id'));

        // Filter down to one Venue or Room
        if ($venue) {
            $orders->onlyVenue($venue);
        }

        if ($room) {
            $orders->onlyRoom($room);
        }

        if ($state) {
            $orders->onlyState($state);
        }

        // Filter by Date-Range
        if ($request->has('from')) {
            $from = new Carbon($request->input('from'));
            $days = $request->input('days') ?? 7;

            $orders->inDateRange($from, $days);
        }

        $orders = $orders->orderBy('created_at')
            ->paginate()
            ->withQueryString();

        $view_data = compact('venues', 'orders');

        if (isset($from)) {
            $view_data = array_merge($view_data, compact('from', 'days'));
        }

        return view('dashboard.index', $view_data);
    }
}
