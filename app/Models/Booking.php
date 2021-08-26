<?php

namespace App\Models;

use App\Models\Order;
use App\Models\package;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'starts_at',
        'ends_at',
        'package_name',
        'quantity',
        'unit_price',
        'vat',
        'deposit',
        'is_flat',
        'snapshot',
        'room_id',
        'package_id',
        'order_id',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'package_snapshot' => 'array',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function package()
    {
        return $this->belongsTo(package::class);
    }
}
