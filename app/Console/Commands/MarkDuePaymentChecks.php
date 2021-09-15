<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Venue;
use Illuminate\Console\Command;

class MarkDuePaymentChecks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bapp:payment-checks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Count and store due payment checks.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Venue::all()->map(function ($venue) {
            $dueToday = $venue->duePaymentChecks();

            // TODO: Is there some kind of bulk update in Laravel?
            $dueToday->map(function ($order) {
                $order->update(['needs_check' => true]);
            });

            $orders = Order::where('venue_id', $venue->id)
                ->where('deposit_paid_at', null)
                ->where('needs_check', true)
                ->get();

            $venue->update([
                'check_count' => $orders->count(),
            ]);
        });

        return 0;
    }
}
