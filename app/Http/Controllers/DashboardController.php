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
        // LATER: duplicated in CustomerController ... DRY!!!
        $venues = auth()->user()->venues()->get();

        $paymentChecks = $venues->filter(
            fn($venue) => $venue->check_count > 0
        );

        $orders = Order::with('latestAction')
            ->whereIn('venue_id', $venues->pluck('id'))
            ->filter($filters)
            ->orderBy('starts_at')
            ->paginate()
            ->withQueryString();

        // LATER: duplicated in CustomerController ... DRY!!!
        $newOrderCount = Order::where('state', 'fresh')
            ->whereBetween(
                'created_at',
                [now()->startOfDay()->subDays(1), now()]
            )->count();

        return view('dashboard.index', compact('venues', 'orders', 'paymentChecks', 'newOrderCount'));
    }
}
