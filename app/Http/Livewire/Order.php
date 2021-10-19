<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\Invoice;
use Illuminate\Support\Carbon;
use App\Events\OrderHasChanged;
use App\Mail\ConfirmationEmail;
use App\Models\Order as OrderModel;
use Illuminate\Support\Facades\Mail;
use App\Events\InvoiceEmailRequested;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Order extends Component
{
    use AuthorizesRequests;

    public $order;

    public $notes;
    public $selectedState;
    public $cash;

    public $dirty = false;

    protected $colors = [
        'fresh' => 'border-red-500',
        'cancelled' => 'border-red-500',
        'deposit_paid' => 'border-yellow-500',
        'interim_paid' => 'border-blue-500',
        'final_paid' => 'border-green-500',
    ];

    public $rules = [
        'order.cash_payment' => 'required|boolean',
        'order.state' => 'required|string',
    ];

    protected $listeners = [
        'updateBookings' => 'bookingsUpdated',       //$refresh',
        'updateItems'    => 'itemsUpdated',          //$refresh',
        'updateCustomer' => 'logCustomerDataChange', //$refresh'
    ];

    public function mount($order)
    {
        $this->order = $order;
        $this->notes = $order->notes;
        $this->selectedState = $order->state;
        $this->cash = $order->cash_payment;
    }

    public function render()
    {
        return view('livewire.order');
    }

    public function updated()
    {
        $this->dirty = true;
    }

    public function save()
    {
        $this->authorize('modify orders', $this->order);

        $this->handleStateChange();
        $this->logNotesChange();

        $this->order->update(
            array_merge([
                'notes' => $this->notes,
                'state' => $this->selectedState,
                'cash_payment' => $this->cash,
            ], $this->updatedTimestamps())
        );

        $this->dirty = false;
        $this->editingNote = false;
    }

    public function cancel()
    {
        $this->notes = $this->order->notes;
        $this->selectedState = $this->order->state;
        $this->cash = $this->order->cash_payment;

        $this->dirty = false;
        $this->editingNote = false;
    }

    public function getColorProperty() : string
    {
        return $this->colors[$this->order->state] ?? '';
    }

    public function updatedTimestamps() : array
    {
        if (! $this->stateHasChanged()) {
            return [];
        }

        $timestamps = [];

        switch ($this->selectedState) {
            case 'fresh':
                // $timestamps['deposit_paid_at'] = null;
                // $timestamps['interim_paid_at'] = null;
                // $timestamps['final_paid_at'] = null;
                // $timestamps['cancelled_at'] = null;
                break;

            case 'deposit_paid':
                $timestamps['deposit_paid_at'] = Carbon::now();
                // $timestamps['interim_paid_at'] = null;
                // $timestamps['final_paid_at'] = null;
                // $timestamps['cancelled_at'] = null;
                break;

            case 'interim_paid':
                $timestamps['interim_paid_at'] = Carbon::now();
                // $timestamps['final_paid_at'] = null;
                // $timestamps['cancelled_at'] = null;
                break;

            case 'final_paid':
                $timestamps['final_paid_at'] = Carbon::now();
                // $timestamps['cancelled_at'] = null;
                break;

            case 'cancelled':
                $timestamps['cancelled_at'] = Carbon::now();
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

        if ($updatedFields = $invoice->updatedFields()) {
            $order->update($updatedFields);
        }

        return $invoice->makePdf();
    }

    public function sendEmail(string $type)
    {
        InvoiceEmailRequested::dispatch($type, $this->order);
    }

    public function stateHasChanged()
    {
        return $this->order->state !== $this->selectedState;
    }

    public function handleStateChange()
    {
        if ($this->stateHasChanged()) {
            $this->logStateChange();
            $this->sendConfirmationEmail();
            $this->updatePaymentChecks();
        }
    }

    public function updatePaymentChecks()
    {
        if (
            $this->order->state === 'fresh' &&
            $this->selectedState !== 'fresh' &&
            $this->order->needs_check
        ) {
            $this->order->update(['needs_check' => false]);
            $this->order->venue->decrement('check_count');
        }
    }

    public function sendConfirmationEmail()
    {
        if ($this->order->state === 'fresh' && $this->selectedState === 'deposit_paid') {
            Mail::to($this->order->customer->email)
                ->queue(new ConfirmationEmail($this->order));
        }
    }

    public function bookingsUpdated()
    {
        $bookingData['starts_at'] = $this->firstBookingDate($this->order->bookings);

        if ($this->order->deposit_paid_at === null) {
            $bookingData['deposit_amount'] = $this->order->deposit;
            $bookingData['interim_amount'] = $this->order->grossTotal - $this->order->deposit;
        } elseif ($this->order->deposit_paid_at && $this->order->interim_paid_at === null) {
            $bookingData['interim_amount'] = $this->order->grossTotal - $this->order->deposit;
        } else {
            // TODO FALSCH FALSCH FALSCH
            $bookingData['interim_is_final'] = false;
        }

        $this->order->update($bookingData);

        $this->logBookingsChange();
    }

    // LATER: Duplicated in Livewire\Order ... BAAAAD!!!
    protected function firstBookingDate($bookings)
    {
        return new Carbon(
            collect($bookings)
                ->pluck('starts_at')
                ->sort()
                ->values()
                ->first()
            );
    }

    public function itemsUpdated()
    {
        // TODO TODO: I guess this might produce a lot of false positives
        if ($this->order->items->count()) {
            $this->order->update([
                'interim_is_final' => false
            ]);
        }
    }


    ////////////////
    // Action Log //
    ////////////////
    public function logStateChange()
    {
        if ($this->stateHasChanged()) {
            OrderHasChanged::dispatch($this->order, auth()->user(), 'state', $this->order->state, $this->selectedState);
        }
    }

    public function logNotesChange()
    {
        if ($this->order->notes !== $this->notes) {
            OrderHasChanged::dispatch($this->order, auth()->user(), 'notes', $this->order->notes ?? '', $this->notes);
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
