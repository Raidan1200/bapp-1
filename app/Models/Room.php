<?php

namespace App\Models;

use App\Models\Venue;
use App\Models\Booking;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'slogan',
        'description',
        'image',
        'capacity',
        'venue_id',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function setNameAttribute($value) {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::of($value)->slug('-');
    }
}
