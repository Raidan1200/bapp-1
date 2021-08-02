<?php

namespace App\Models;

use App\Models\Venue;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slogan',
        'description',
        'image',
        'capacity',
        'venue_id',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
