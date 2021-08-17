<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Venue;
use App\Models\Customer;
use App\Mail\ReminderEmail;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReminderEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send billing and reminder emails to customers and employees';

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
            $venue->overdueOrders->map(function($order) {
                $this->sendEmail($order);
            });
        }

        return 0;
    }

    public function sendEmail(Order $order)
    {
        Mail::to($order->customer->email)
            ->send(new ReminderEmail($order));
    }
}
