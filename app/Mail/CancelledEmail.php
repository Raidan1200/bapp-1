<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CancelledEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $pdf;

    public function __construct(Order $order, string $pdf)
    {
        $this->order = $order;
        $this->pdf = $pdf;
    }

    public function build()
    {
        $invoice_id =
            $this->order->final_invoice_id ??
            $this->order->interim_invoice_id ??
            $this->order->deposit_invoice_id;

        $filename = 'rechnung-'.$invoice_id.'-S.pdf';

        $email = $this
            ->from($this->order->venue->email)
            ->subject('Stornierung der Buchung von ' . $this->order->venue->name);

        if ($invoice_id) {
            $email->attachData($this->pdf, $filename, [
                'mime' => 'application/pdf',
            ]);
        }

        return $email->view('emails.cancelled');
    }
}
