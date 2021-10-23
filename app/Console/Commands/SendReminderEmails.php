<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Venue;
use App\Mail\ReminderEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReminderEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bapp:reminder-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send payment reminder emails to customers';

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
            $venue->dueReminderEmails()->map(function($order) {
                $this->sendReminderEmail($order);
                $order->update([
                    'deposit_reminder_at' => now(),
                ]);
            });
        }

        return 0;
    }

    public function sendReminderEmail(Order $order)
    {
        Mail::to($order->customer->email)
            ->send(new ReminderEmail($order));
    }
}
