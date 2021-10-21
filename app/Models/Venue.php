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
        'config',
        'check_count',
        'invoice_id',
        'invoice_id_format',
    ];

    protected $casts = [
        'config' => 'array',
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
        return $this
            ->due('reminder_delay')
            ->whereNull('deposit_reminder_at')
            ->get();
    }

    public function duePaymentChecks()
    {
        return $this
            ->due('check_delay')
            ->where('needs_check', false)
            ->get();
    }

    public function dueOrderCancellations()
    {
        return $this
            ->due('not_paid_delay')
            ->get();
    }

    public function scopeDue($query, string $thing)
    {
        $delay = $this->config['delays'][$thing];

        // TODO - delay syntax
        // $this->$field_name - 1  => act on the configured delay day
        // $this->$field_name  => act after at least delay days have passed
        $day = now()->startOfDay()->subDays($delay);

        return $this->orders()
            ->where('state', 'fresh')
            ->where('created_at', '<', $day);
    }

    public function getNextInvoiceId()
    {
        $invoice_id = sprintf($this->invoice_id_format, $this->next_invoice_id);

        $this->increment('next_invoice_id');
        return $invoice_id;
    }
}
