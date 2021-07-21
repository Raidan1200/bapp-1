<?php

namespace App\Models;

use App\Models\Venue;
use App\Models\Booking;
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
        'venue_id'
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
}
