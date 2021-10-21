<?php

namespace App\Models;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'unit_price',
        'vat',
        'config',
        'venue_id',
    ];

    protected $casts = [
        'config' => 'array',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}
