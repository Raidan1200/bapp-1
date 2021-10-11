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
        'slug',
        'email',
        'invoice_blocks',
        'reminder_delay',
        'check_delay',
        'check_count',
        'delete_delay',
        'invoice_id',
    ];

    protected $casts = [
        'invoice_blocks' => 'array',
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

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function dueEmailReminders()
    {
        return $this->due('reminder')->fresh()->get();
    }

    public function duePaymentChecks()
    {
        return $this->due('check')->fresh()->get();
    }

    public function dueOrderDeletions()
    {
        return $this->due('delete')->fresh()->get();
    }

    public function scopeDue($query, string $thing)
    {
        $attribute = $thing . '_delay';

        return $this->orders()->whereBetween('created_at', [
            now()->startOfDay()->subDays($this->$attribute),
            now()->startOfDay()->subDays($this->$attribute - 1)
        ]);
    }

    public function getNextInvoiceId()
    {
        $id = $this->next_invoice_id;

        $this->increment('next_invoice_id');

        return $id;
    }
}
