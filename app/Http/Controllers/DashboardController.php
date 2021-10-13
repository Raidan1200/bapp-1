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
        $venues = auth()->user()->venues()->get();

        // TODO: duplicated in CustomerController ... DRY!!!
        $paymentChecks = $venues->filter(
            fn($venue) => $venue->where('check_count', '>', 0)
        );

        $orders = Order::with('latestAction')
            ->whereIn('venue_id', $venues->pluck('id'))
            ->filter($filters)
            ->orderBy('starts_at')
            ->paginate()
            ->withQueryString();

        // TODO: duplicated in CustomerController ... DRY!!!
        $newOrderCount = Order::where('state', 'fresh')
            ->whereBetween(
                'created_at',
                [now()->startOfDay()->subDays(1), now()]
            )->count();

        return view('dashboard.index', compact('venues', 'orders', 'paymentChecks', 'newOrderCount'));
    }
}
