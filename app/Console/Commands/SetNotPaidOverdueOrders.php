<?php

namespace App\Console\Commands;

use App\Models\Venue;
use Illuminate\Console\Command;

class SetNotPaidOverdueOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bapp:set-not-paid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set state of orders where deposit grace period is over to "not paid".';

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
        foreach (Venue::all() as $venue) {
            // TODO rename function
            $venue->dueOrderCancellations()->map(function($order) {
                $order->update([
                    'state' => 'not_paid',
                    'cancelled_at' => now(),
                ]);
            });
        }

        return 0;
    }
}
