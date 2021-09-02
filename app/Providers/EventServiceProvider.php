<?php

namespace App\Providers;

use App\Events\OrderHasChanged;
use App\Listeners\LogOrderChange;
use App\Listeners\SendInvoiceEmail;
use App\Events\InvoiceEmailRequested;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        InvoiceEmailRequested::class => [
            SendInvoiceEmail::class,
        ],
        OrderHasChanged::class => [
            LogOrderChange::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
