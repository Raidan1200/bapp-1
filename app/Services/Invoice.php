<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Carbon;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class Invoice
{
    protected $type = '';
    protected $types = ['deposit', 'interim', 'final', 'cancelled'];

    protected $date;

    protected $vats = [];
    protected $netTotal = 0;
    protected $grossTotal = 0;

    protected $updatedFields = [];

    protected $order;

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
        return $this;
    }

    public function calculate()
    {
        $this->setDate();
        $this->prepareData();
        $this->setUpdatedFields();
        return $this;
    }

    public function updatedFields()
    {
        return $this->updatedFields;
    }

    public function makePdf()
    {
        return PDF::loadView(
            "pdf.{$this->type}_invoice", [
                'date' => $this->date,
                'venue' => $this->order->venue,
                'customer' => $this->order->customer,
                'order' => $this->order,
                'vats' => $this->vats,
                'net_total' => $this->netTotal,
                'gross_total' => $this->grossTotal,
            ]
        );
    }

    protected function setDate()
    {
        $at_field = $this->type . '_invoice_at';

        $this->date = $this->order->$at_field ?: Carbon::now();
        $this->updatedFields[$at_field] = $this->date;
    }

    protected function prepareData()
    {
        foreach ($this->order->bookings as $booking) {
            if (isset($this->vats[$booking->vat])) {
                $this->vats[$booking->vat] += $booking->vatAmount;
            } else {
                $this->vats[$booking->vat] = $booking->vatAmount;
            }

            $this->netTotal += $booking->netTotal;
            $this->grossTotal += $booking->grossTotal;
        }

        foreach ($this->order->items as $item) {
            if (isset($vat[$item->vat])) {
                $vat[$item->vat] += $item->vatAmount;
            } else {
                $vat[$item->vat] = $item->vatAmount;
            }

            $this->netTotal += $item->netTotal;
            $this->netTotal += $item->grossTotal;
        }

        foreach ($this->vats as &$value) {
            $value = round($value);
        }
    }

    protected function setUpdatedFields()
    {
        // TODO: Always? Or just if it is not already set?
        //       Maybe this should be set in the corresponding Controller!
        //       Because after the deposit email was sent, it's supposed to be immutable
        if (in_array($this->type, ['deposit', 'interim'])) {
            $amount_field = $this->type . '_amount';

            $this->updatedFields[$amount_field] = $this->grossTotal;
        }
    }
}
