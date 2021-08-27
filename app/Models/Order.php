<?php

namespace App\Models;

use App\Models\Venue;
use App\Models\Action;
use Brick\Money\Money;
use App\Models\Booking;
use App\Filters\QueryFilter;
use Brick\Math\RoundingMode;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
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
        'deposit_invoice_at',
        'deposit_paid_at',
        'deposit_amount',
        'interim_invoice_at',
        'interim_paid_at',
        'interim_amount',
        'final_invoice_at',
        'final_paid_at',
        'cancelled_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'deposit_invoice_at' => 'datetime',
        'deposit_paid_at' => 'datetime',
        'interim_invoice_at' => 'datetime',
        'interim_paid_at' => 'datetime',
        'final_invoice_at' => 'datetime',
        'final_paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    protected $with = ['bookings', 'customer'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
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

    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }

    public function getDepositAttribute()
    {
        return $this->bookings->reduce(function ($deposit, $booking) {
            return $deposit->plus(
                Money::ofMinor($booking->unit_price, 'EUR')
                    ->toRational()
                    ->multipliedBy($booking->quantity)
                    ->multipliedBy($booking->deposit / 100)
                    ->to($deposit->getContext(), RoundingMode::DOWN) // TODO: DOWN?
            );
        }, Money::ofMinor('0', 'EUR'))
        ->formatTo('de_DE');
    }

    public function getTotalAttribute()
    {
        return $this->bookings->reduce(function ($sum, $booking) {
            return $sum->plus(
                Money::ofMinor($booking->unit_price, 'EUR')
                ->multipliedBy($booking->quantity)
                ->to($sum->getContext(), RoundingMode::DOWN) // TODO: DOWN?
            );
        }, Money::ofMinor('0', 'EUR'))
        ->formatTo('de_DE');
    }
}
