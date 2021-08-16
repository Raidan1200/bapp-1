<?php

namespace App\Models;

use App\Models\Venue;
use App\Models\Action;
use App\Models\Booking;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'status',
        'cash_payment',
        'deposit',
        'notes',
        'customer_id',
        'venue_id',
        'starts_at',
        'deposit_email_at',
        'deposit_paid_at',
        'interim_email_at',
        'interim_paid_at',
        'final_email_at',
        'final_paid_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'deposit_email_at' => 'datetime',
        'deposit_paid_at' => 'datetime',
        'interim_email_at' => 'datetime',
        'interim_paid_at' => 'datetime',
        'final_email_at' => 'datetime',
        'final_paid_at' => 'datetime',
    ];

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
        return $this->hasOne(Action::class)->latest();
    }

    public function scopeOnlyVenue($query, $venue_id)
    {
        return $query->where('venue_id', $venue_id);
    }

    public function scopeOnlyRoom($query, $room_id)
    {
        return $query->whereHas('bookings', fn($q) => $q->where('room_id', $room_id));
    }

    public function scopeOnlyState($query, $state)
    {
        return $query->where('status', $state);
    }

    public function scopeInDateRange($query, $from, $days)
    {
        // TODO: Juggling timezones like this seems kind of hacky, but it works
        $from = (new Carbon($from))->shiftTimezone('Europe/Berlin')->timezone('UTC');

        return $query->whereBetween('starts_at', [$from, (new Carbon($from))->addDays($days)]);
    }
}
