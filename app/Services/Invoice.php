<?php

namespace App\Services;

use App\Models\Order;
use App\Services\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Invoice
{
    // TODO TODO move into DB:Venue
    private const PAYMENT_DELAY = 7;

    public $invoiceId;
    public $type = '';
    public $date;
    public $order;
    public $subject = '';
    public $text = [];

    private $dest = 'I';

    protected $types = ['deposit', 'interim', 'final', 'cancelled'];
    protected $updatedFields = [];

    const SUBJECTS = [
        'deposit' => 'Anzahlungsrechnung',
        'interim' => 'Abschlussrechnung',
        'final' => 'Gesamtrechnung',
        'cancelled' => 'Stornorechnung',
    ];

    public function ofType(string $type)
    {
        if (!in_array($type, $this->types)) {
            throw new \Exception('Unknown Invoice Type: ' . $this->type ?? 'null');
        }

        $this->type = $type;
        return $this;
    }

    public function forOrder(Order $order)
    {
        $this->order = $order;

        $this->setDate();
        $this->setInvoiceId();
        $this->setSubject();
        $this->setText();

        return $this;
    }

    public function invoiceId()
    {
        return $this->invoiceId;
    }

    public function asString()
    {
        $this->dest = 'S';
        return $this;
    }

    public function asStream()
    {
        $this->dest = 'I';
        return $this;
    }

    public function makePdf()
    {
        if ($this->updatedFields) {
            $this->order->update($this->updatedFields);
        }

        $file_path = $this->order->venue->slug.'/invoices/'.$this->invoiceId.'.pdf';
        $cancelled_path = $this->order->venue->slug.'/invoices/'.$this->invoiceId.'-S.pdf';

        if ($this->type === 'cancelled' && Storage::disk('public')->missing($file_path)) {
            throw new \Exception('The invoice you are trying to cancel doesn\'t yet exist.');
        }

        if ($this->type !== 'cancelled') {
            // Generate PDF file if it doesn't exist already
            // Regenerate PDF file if the corresponding invoice hasn't been paid yet

            if (
                Storage::disk('public')->missing($file_path) ||
                (
                    $this->type === 'deposit' && $this->order->deposit_paid_at === null ||
                    $this->type === 'interim' && $this->order->interim_paid_at === null ||
                    $this->type === 'final' && $this->order->final_paid_at === null
                )
            ) {
                $data = [
                    'invoice_id' => $this->invoiceId,
                    'type' => $this->type,
                    'date' => $this->date,
                    'subject' => $this->subject,
                    'order' => $this->order,
                    'text' => $this->text,
                ];

                Storage::disk('public')->put($file_path, (new Pdf($data))->output('S'));

                $data['subject'] = self::SUBJECTS['cancelled'];
                $data['invoice_id'] = $this->invoiceId . '-S';

                Storage::disk('public')->put($cancelled_path, (new Pdf($data))->output('S'));
            }
        }

        if ($this->dest === 'I') {
            return Storage::disk('public')->download($file_path);
        }

        if ($this->dest === 'S') {
            return Storage::disk('public')->get($file_path);
        }
    }

    protected function setInvoiceId()
    {
        if (!$this->type) {
            throw new \Exception('You must set the Invoice type first.');
        }

        // Get ID of "latest" invoice - latest by business logic
        if ($this->type === 'cancelled') {
            if ($this->order->final_invoice_at) {
                $this->invoiceId = $this->order->final_invoice_id . '-S';
            } elseif ($this->order->interim_invoice_at) {
                $this->invoiceId = $this->order->interim_invoice_id . '-S';
            } elseif ($this->order->deposit_invoice_at) {
                $this->invoiceId = $this->order->deposit_invoice_id . '-S';
            }

            return $this;
        }

        $id_field = $this->type . '_invoice_id';
        $this->invoiceId = $this->order->$id_field;

        if ($this->invoiceId === null) {
            $this->invoiceId = $this->order->venue->getNextInvoiceId();
            $this->updatedFields[$id_field] = $this->invoiceId;
        }

        return $this;
    }

    protected function setDate()
    {
        $at_field = $this->type . '_invoice_at';

        $this->date = $this->order->$at_field;

        if ($this->date === null) {
            $this->date = Carbon::now();
            $this->updatedFields[$at_field] = $this->date;
        }

        return $this;
    }

    protected function setSubject($type = null)
    {
        $this->subject = self::SUBJECTS[$type ?? $this->type];

        return $this;
    }

    protected function setText()
    {
        $payment_delay = $this->order->venue->config['delays']['payment_delay'];

        switch ($this->type) {
            case 'deposit':
                $this->text = [
                    'Bitte überweisen Sie den Betrag von ' . money($this->order->deposit_amount) . ' Euro bis zum ' .
                    $this->order->created_at->addDays($payment_delay)->format('d.m.Y') .
                    ' unter Angabe der Rechnungsnummer auf das genannte Konto der Ostsächsische Sparkasse Dresden.'
                    ,
                    'BITTE BEACHTEN SIE: Der Geldeingang muss bis spätestens 7 Werktage nach Ihrer Reservierung erfolgt sein, spätere Eingänge werden nicht mehr berücksichtigt und die betreffende Bestellung wird automatisch storniert.'
                ];
                break;
            case 'interim':
                // TODO TODO copied from Pdf.php
                if ($this->type === 'deposit') {
                    $grossTotal = $this->order->deposit_amount;

                } elseif ($this->type === 'interim') {
                    // TODO TODO Isnt't this just the interim_amount???
                    $grossTotal = $this->order->grossTotal;

                    if ($this->order->deposit_paid_at) {
                        $grossTotal -= $this->order->deposit_amount;
                    }

                } elseif ($this->type === 'final') {
                    $grossTotal = $this->order->grossTotal;

                    if ($order->deposit_paid_at) {
                        $grossTotal -= $this->order->deposit_amount;
                    }

                    if ($order->interim_paid_at) {
                        $grossTotal -= $this->order->interim_amount;
                    }

                } elseif ($this->type === 'cancelled') {
                    //
                }

                $this->text = [
                    'Bitte überweisen Sie den Betrag von ' . money($grossTotal) . ' Euro bis zum ' .
                    ($this->order->interim_invoice_at ?: now())->addDays($payment_delay)->format('d.m.Y') .
                    ' unter Angabe der Rechnungsnummer auf das genannte Konto der Ostsächsische Sparkasse Dresden.'
                    ,
                ];
                break;
            case 'final':
                // TODO TODO copied from Pdf.php
                $grossTotal = $this->order->grossTotal;

                if ($this->order->deposit_paid_at) {
                    $grossTotal -= $this->order->deposit_amount;
                }

                if ($this->order->interim_paid_at) {
                    $grossTotal -= $this->order->interim_amount;
                }

                $this->text = [
                    'Bitte überweisen Sie den Betrag von ' . money($grossTotal) . ' Euro bis zum ' .
                    ($this->order->final_invoice_at ?: now())->addDays($payment_delay)->format('d.m.Y') .
                    ' unter Angabe der Rechnungsnummer auf das genannte Konto der Ostsächsische Sparkasse Dresden.'
                    ,
                ];

                break;
            case 'cancelled':
                $this->text = [
                    'Stornorechnung',
                ];
                break;
        }

        if (
            $this->order->cash_payment &&
            ! in_array($this->order->type, ['deposit', 'cancelled']) &&
            (
                $this->type === 'final' && $this->order->final_paid_at
                ||
                $this->type === 'interim' && $this->order->interim_paid_at && $this->order->interim_is_final
            )
        ) {
            $this->text[] = 'Die Rechnung wurde am Veranstaltungstag in Bar beglichen!';
        }

        $this->text[] = 'Es gelten unser allgemeinen Geschäfts- und Vertragsbedingungen.';
    }
}
