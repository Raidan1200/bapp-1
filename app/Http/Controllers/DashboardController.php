<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Venue;
use Illuminate\Http\Request;
use App\Filters\OrderFilters;

class DashboardController extends Controller
{
    public function index(OrderFilters $filters, Request $request)
    {
        $venues = auth()->user()->venues()->with(['rooms' => function ($q) {
            $q->orderBy('name')->with(['packages' => function ($r) {
                $r->orderBy('name');
            }]);
        }])->get();

        // TODO IMPORTANT: This is highly inefficient!!!
        //     Just store a cached count during the nightly cron run
        //     and move this to a separate page
        $reminders = collect([]);

        foreach ($venues as $venue) {
            $reminders = $reminders->merge($venue->duePaymentChecks());
        }

        $orders = Order::with('latestAction')
            ->whereIn('venue_id', $venues->pluck('id'))
            ->filter($filters)
            ->orderBy('starts_at')
            ->paginate()
            ->withQueryString();

        $newOrderCount = Order::where('state', 'fresh')
            ->whereBetween(
                'created_at',
                [now()->startOfDay()->subDays(1), now()]
            )->count();

        return view('dashboard.index', compact('venues', 'orders', 'reminders', 'newOrderCount'));
    }
}
