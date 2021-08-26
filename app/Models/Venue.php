<?php

namespace App\Models;

use App\Models\Room;
use App\Models\User;
use App\Models\Order;
use App\Models\Package;
use App\Models\Product;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venue extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'reminder_delay',
        'check_delay',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class)->orderBy('name');
    }

    public function packages()
    {
        return $this->hasMany(Package::class)->orderBy('name');
    }

    public function products()
    {
        return $this->hasMany(Product::class)->orderBy('name');
    }

    public function getOverdueOrdersAttribute()
    {
        return Order::whereBetween('starts_at', [
            now()->addDays($this->reminder_delay),
            now()->addDays($this->reminder_delay + 1)
        ])->get();
    }
}
