<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Venue;
// use Brick\Money\Money;
use App\Models\Action;
use App\Models\Booking;
use App\Filters\QueryFilter;
use Brick\Math\RoundingMode;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'state',
        'cash_payment',
        'deposit',
        'notes',
        'customer_id',
        'venue_id',
        'starts_at',

        'deposit_invoice_id',
        'deposit_invoice_at',
        'deposit_email_at',
        'deposit_paid_at',
        'deposit_amount',
        'needs_check',

        'interim_invoice_id',
        'interim_invoice_at',
        'interim_email_at',
        'interim_paid_at',
        'interim_amount',
        'interim_is_final',

        'final_invoice_id',
        'final_invoice_at',
        'final_email_at',
        'final_paid_at',

        'cancelled_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',

        'deposit_invoice_at' => 'datetime',
        'deposit_email_at' => 'datetime',
        'deposit_reminder_at' => 'datetime',
        'deposit_paid_at' => 'datetime',

        'interim_invoice_at' => 'datetime',
        'interim_email_at' => 'datetime',
        'interim_paid_at' => 'datetime',

        'final_invoice_at' => 'datetime',
        'final_email_at' => 'datetime',
        'final_paid_at' => 'datetime',

        'cancelled_at' => 'datetime',
    ];

    protected $with = ['bookings', 'items', 'customer', 'venue'];

    /*
     * Relations
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function actions()
    {
        return $this->hasMany(Action::class)->orderBy('created_at');
    }

    public function latestAction()
    {
        return $this->hasOne(Action::class)->latestOfMany();
    }


    /*
     * Accessors / Mutators
     */
    public function getDepositAttribute()
    {
        return $this->bookings->reduce(function ($deposit, $booking) {
            return $deposit += ($booking->quantity * $booking->unit_price) * ($booking->deposit / 100);
        });
    }

    public function getNetTotalAttribute()
    {
        return $this->getTotal('netTotal');
    }

    public function getGrossTotalAttribute()
    {
        return $this->getTotal('grossTotal');
    }

    protected function getTotal(string $type)
    {
        return round(
            collect($this->bookings)->sum($type)
            +
            collect($this->items)->sum($type)
        );
    }

    public function getVatsAttribute()
    {
        $vats = collect([]);

        foreach ($this->bookings as $booking) {
            if ($vats->has((string) $booking->vat)) {
                $vats[(string) $booking->vat] += $booking->vatAmount;
            } else {
                $vats[(string) $booking->vat] = $booking->vatAmount;
            }
        }

        foreach ($this->items as $item) {
            if ($vats->has((string) $item->vat)) {
                $vats[(string) $item->vat] += $item->vatAmount;
            } else {
                $vats[(string) $item->vat] = $item->vatAmount;
            }
        }

        return $vats->map(fn($vat) => round($vat));
    }

    /*
     * Misc
     */
    public function scopeFilter(Builder $query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }

    public function scopeFresh(Builder $query)
    {
        return $query->where('state', 'fresh');
    }
}
