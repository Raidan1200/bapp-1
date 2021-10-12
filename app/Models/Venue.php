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
        'cancel_delay',
        'invoice_id',
        'invoice_id_format',
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
        return $this
            ->due('reminder')
            ->whereNull('deposit_reminder_at')
            ->get();
    }

    public function duePaymentChecks()
    {
        return $this
            ->due('check')
            ->where('needs_check', false)
            ->get();
    }

    public function dueOrderCancellations()
    {
        return $this
            ->due('cancel')
            ->where('state', '<>', 'cancelled')
            ->get();
    }

    public function scopeDue($query, string $thing)
    {
        $field_name = $thing . '_delay';
        $delay = $this->$field_name;

        // TODO - delay syntax
        // $this->$field_name - 1  => act on the configured delay day
        // $this->$field_name - 1  => act after at least delay days have passed
        $day = now()->startOfDay()->subDays($this->$field_name);

        return $this->orders()
            // TODO TODO: This or ->whereNull('deposit_paid_at')->whereNull('cancelled_at')...
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
