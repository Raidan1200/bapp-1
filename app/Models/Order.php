<?php

namespace App\Models;

use App\Models\Venue;
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
    ];

    protected $casts = [
        'starts_at' => 'datetime'
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

    public function scopeOnlyVenue($query, $venue_id)
    {
        return $query->where('venue_id', $venue_id);
    }

    public function scopeOnlyRoom($query, $room_id)
    {
        return $query->whereHas('bookings', fn($q) => $q->where('room_id', $room_id));
    }

    public function scopeInDateRange($query, $from, $days)
    {
        return $query->whereBetween('starts_at', [$from, (new Carbon($from))->addDays($days)]);
    }
}
