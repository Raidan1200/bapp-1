<?php

namespace App\Models;

use App\Models\Room;
use App\Models\Venue;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
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
        'deposit',
        'is_flat',
        'unit_price_flat',
        'vat_flat',
        'venue_id',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_flat' => 'boolean',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class);
    }

    public function setNameAttribute($value) {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::of($value)->slug('-');
    }
}
