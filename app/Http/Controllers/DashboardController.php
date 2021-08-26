<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Venue;
use Illuminate\Http\Request;
use App\Filters\OrderFilters;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(OrderFilters $filters, Request $request)
    {
        $venues = auth()->user()->venues()->with(['rooms' => function ($q) {
            $q->orderBy('name')->with(['packages' => function ($r) {
                $r->orderBy('name');
            }]);
        }])->get();

        $orders = Order::with('latestAction')
            ->whereIn('venue_id', $venues->pluck('id'))
            ->filter($filters)
            ->orderBy('starts_at')
            ->paginate()
            ->withQueryString();

        return view('dashboard.index', compact('venues', 'orders'));
    }
}
