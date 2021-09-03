<?php

namespace App\Console\Commands;

use App\Models\Venue;
use Illuminate\Console\Command;

class DeleteOverdueOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bapp:delete-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete orders where deposit grace period is over.';

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
            $venue->dueOrderDeletions()->map(function($order) {
                $order->delete();
            });
        }

        return 0;
    }
}
