<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DepositEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.deposit')
            ->with([
                'test' => 12,
                'deposit' => $this->deposit(),
            ]);
    }

    // TODO: This is duplicated in the Livewire Order component !!! BAD!
    public function total()
    {
        return $this->order->bookings->reduce(function ($sum, $booking) {
            return $sum += $booking->quantity * $booking->unit_price;
        });
    }

    public function deposit()
    {
        return $this->order->bookings->reduce(function ($deposit, $booking) {
            return $deposit += ($booking->quantity * $booking->unit_price) * ($booking->deposit / 100);
        });
    }

}
