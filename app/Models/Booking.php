<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'starts_at',
        'ends_at',
        'quantity',
        'room_id',
        'product_id',
        'order_id',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'product_snapshot' => 'array',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
