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

    public function getGrossTotalAttribute()
    {
        // TODO: IF HAS RATE HOURLY
        return $this->unit_price * $this->quantity;
    }

    public function getNetTotalAttribute()
    {
        return $this->grossTotal / (($this->vat / 100) + 1);
    }

    public function getGrossDepositAttribute()
    {
        // TODO: IF HAS RATE HOURLY
        return $this->unit_price * $this->deposit / 100;
    }

    public function getGrossDepositTotalAttribute()
    {
        return $this->grossDeposit * $this->quantity;
    }

    public function getNetDepositTotalAttribute()
    {
        return $this->grossDepositTotal / (($this->vat / 100) + 1);
    }

    public function getVatAmountAttribute()
    {
        return $this->grossTotal - $this->netTotal;
    }

    public function getDepositVatAmountAttribute()
    {
        return $this->grossDepositTotal - $this->netDepositTotal;
    }
}
