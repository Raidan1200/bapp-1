<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Carbon;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;

class Invoice
{
    protected $type;
    protected $types = ['deposit', 'interim', 'final', 'cancelled'];

    protected $date;

    protected array $data;

    protected array $updatedFields;

    protected Order $order;

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

        $invoiceClass = '\\App\\Services\\' . ucfirst($this->order->venue->slug) . 'Invoice';

        $this->data = (new $invoiceClass)->prepareData($this->type, $this->order);

        if (in_array($this->type, ['deposit', 'interim'])) {
            $amount_field = $this->type . '_amount';

            $this->updatedFields[$amount_field] = $this->data['total'];
        }

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
                'data' => $this->data,
            ]
        );
    }

    protected function setDate()
    {
        $at_field = $this->type . '_invoice_at';

        $this->date = $this->order->$at_field ?: Carbon::now();
        $this->updatedFields[$at_field] = $this->date;
    }
}
