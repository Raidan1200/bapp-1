<?php

namespace App\Models;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'cash_payment',
        'deposit',
        'notes',
        'customer_id',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
