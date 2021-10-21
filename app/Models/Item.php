<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'quantity',
        'unit_price',
        'vat',
        'config',
        'order_id',
    ];

    protected $casts = [
        'config' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // LATER: Duplicated from Booking-Model
    public function getGrossTotalAttribute()
    {
        return $this->unit_price * $this->quantity;
    }

    public function getNetTotalAttribute()
    {
        return $this->grossTotal / (($this->vat / 100) + 1);
    }

    public function getVatAmountAttribute()
    {
        return $this->grossTotal - $this->netTotal;
    }
}
