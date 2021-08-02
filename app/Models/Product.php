<?php

namespace App\Models;

use App\Models\Venue;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slogan',
        'description',
        'image',
        'starts_at',
        'ends_at',
        'opens_at',
        'closes_at',
        'min_occupancy',
        'unit_price',
        'vat',
        'is_flat',
        'unit_price_flat',
        'vat_flat',
        'deposit',
        'room_id',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
