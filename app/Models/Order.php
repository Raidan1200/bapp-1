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
        'customer',
    ];

    protected $casts = [
        'customer' => 'array',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
