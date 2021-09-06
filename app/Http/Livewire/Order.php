<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\Invoice;
use Illuminate\Support\Carbon;
use App\Events\OrderHasChanged;
use App\Models\Order as OrderModel;
use App\Events\InvoiceEmailRequested;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Order extends Component
{
    use AuthorizesRequests;

    public $order;

    public $notes;
    public $selectedState;

    public $dirty = false;

    public $test = 'Nix';

    protected $colors = [
        'fresh' => 'border-red-500',
        'cancelled' => 'border-red-500',
        'deposit_paid' => 'border-yellow-500',
        'interim_paid' => 'border-blue-500',
        'final_paid' => 'border-green-500',
    ];

    protected $listeners = [
        'updateBookings' => 'logBookingsChange',     //$refresh',
        'updateItems'    => 'logItemsChange',        //$refresh',
        'updateCustomer' => 'logCustomerDataChange', //$refresh'
    ];

    public function mount($order)
    {
        $this->order = $order;
        $this->notes = $order->notes;
        $this->selectedState = $order->state;
    }

    public function updated()
    {
        $this->dirty = true;
    }

    public function save()
    {
        $this->authorize('modify orders', $this->order);

        $this->logStateChange();
        $this->logNotesChange();

        $this->order->update(
            array_merge([
                'notes' => $this->notes,
                'state' => $this->selectedState,
            ], $this->updatedTimestamps())
        );

        $this->dirty = false;
        $this->editingNote = false;
    }

    public function cancel()
    {
        $this->selectedState = $this->order->state;

        $this->dirty = false;
        $this->editingNote = false;
    }

    public function getColorProperty() : string
    {
        return $this->colors[$this->order->state] ?? '';
    }

    public function render()
    {
        return view('livewire.order');
    }

    public function updatedTimestamps() : array
    {
        if (! $this->stateHasChanged()) {
            return [];
        }

        $timestamps = [];

        switch ($this->order->state) {
            case 'fresh':
                $timestamps['deposit_paid_at'] = null;
                $timestamps['interim_paid_at'] = null;
                $timestamps['final_paid_at'] = null;
                break;

            case 'deposit_paid':
                $timestamps['deposit_paid_at'] = Carbon::now();
                $timestamps['interim_paid_at'] = null;
                $timestamps['final_paid_at'] = null;
                break;

            case 'interim_paid':
                $timestamps['interim_paid_at'] = Carbon::now();
                $timestamps['final_paid_at'] = null;
                break;

            case 'final_paid':
                $timestamps['final_paid_at'] = Carbon::now();
                break;
        }

        return $timestamps;
    }

    public function makeInvoice(string $type)
    {
        $order = OrderModel::findOrFail($this->order->id)->load('venue');

        $invoice = (new Invoice)
            ->ofType($type)
            ->forOrder($order);

        $order->update($invoice->updatedFields());

        return $invoice
            ->makePdf()
            ->stream("invoice_{$this->order->invoice_id}.pdf"); // TODO: Filename?
    }

    public function sendEmail(string $type)
    {
        InvoiceEmailRequested::dispatch($type, $this->order);
    }

    public function toggleCashPayment()
    {
        $this->order->update([
            'cash' => ! $this->order->cash
        ]);
    }

    // Action Log
    public function stateHasChanged()
    {
        return $this->order->state !== $this->selectedState;
    }

    public function logStateChange()
    {
        if ($this->stateHasChanged()) {
            OrderHasChanged::dispatch($this->order, auth()->user(), 'state', $this->order->state, $this->selectedState);
        }
    }

    public function logNotesChange()
    {
        if ($this->order->notes !== $this->notes) {
            OrderHasChanged::dispatch($this->order, auth()->user(), 'notes', $this->order->notes, $this->notes);
        }
    }

    public function logCustomerDataChange()
    {
        OrderHasChanged::dispatch($this->order, auth()->user(), 'customer', '', '');
    }

    public function logBookingsChange()
    {
        OrderHasChanged::dispatch($this->order, auth()->user(), 'bookings', $this->order->bookings->count(), '');
    }

    public function logItemsChange()
    {
        OrderHasChanged::dispatch($this->order, auth()->user(), 'items', $this->order->items->count(), '');
    }
}
