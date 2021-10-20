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
        'fresh' => 'border-gray-400',
        'deposit_paid' => 'border-yellow-500',
        'interim_paid' => 'border-green-500',
        'final_paid' => 'border-blue-500',
        'cancelled' => 'border-gray-400',
        'not_paid' => 'border-gray-400',
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
        // WENN Abschluss oder Gesamtemail gesendet wurde
        //  UND entsprechend ABSCHLUSS bzw GESAMT nicht auf BEZAHLT steht
        //  DANN nach VENUE_CONFIG Tagen Farbe auf ULTRAVIOLETT ändern

        // WENN interim_is_final UND interim_paid UND event vorbei
        //   DANN BLAU

        return $this->colors[$this->order->state] ?? '';
    }

    public function updatedTimestamps() : array
    {
        if (! $this->stateWillChange()) {
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

            case 'not-paid':
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

        return response()->streamDownload(fn() =>
            $invoice->asStream()->makePdf()
        );
    }

    public function sendEmail(string $type)
    {
        $order = OrderModel::findOrFail($this->order->id)->load('venue');

        $invoice = (new Invoice)
            ->ofType($type)
            ->forOrder($order)
            ->asString()
            ->makePdf();

        $email = Mail::to($this->order->customer->email);

        $emailClass = '\\App\\Mail\\' . ucfirst($type) . 'Email';
        $email_sent_field = $type . '_email_at';

        // LATER: How do I handle mail-sent errors ... try catch?
        // LATER: queue throws 'Attempt to read property "name" on null in /var/www/html/storage/framework/views/ed2533...'
        $email->send(new $emailClass($order, $invoice));

        if ($type !== 'cancelled') {
            $order->update([
                $email_sent_field => Carbon::now()
            ]);
        }
    }

    public function sendConfirmationEmail()
    {
        $order = OrderModel::findOrFail($this->order->id)->load('venue');

        Mail::to($this->order->customer->email)
            // LATER: queue throws 'Attempt to read property "name" on null in /var/www/html/storage/framework/views/ed2533...'
            ->send(new ConfirmationEmail($order));
    }

    public function stateWillChange()
    {
        return $this->order->state !== $this->selectedState;
    }

    public function handleStateChange()
    {
        if ($this->stateWillChange()) {
            $this->logStateChange();
            if ($this->order->state === 'fresh' && $this->selectedState === 'deposit_paid') {
                $this->sendConfirmationEmail();
            }
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

    public function bookingsUpdated()
    {
        $bookingData['starts_at'] = $this->firstBookingDate($this->order->bookings);

        if ($this->order->deposit_paid_at === null) {
            $bookingData['deposit_amount'] = $this->order->deposit;
            $bookingData['interim_amount'] = $this->order->grossTotal - $this->order->deposit;
        } elseif ($this->order->deposit_paid_at && $this->order->interim_paid_at === null) {
            $bookingData['interim_amount'] = $this->order->grossTotal - $this->order->deposit;
        } elseif ($this->order->deposit_paid_at && $this->order->interim_paid_at) {
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
        if ($this->stateWillChange()) {
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
