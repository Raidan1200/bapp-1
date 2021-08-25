<?php

namespace App\Providers;

use App\Events\OrderReceived;
use App\Events\OrderHasChanged;
use App\Listeners\LogOrderChange;
use App\Listeners\SendDepositEmail;
use Illuminate\Support\Facades\Event;
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
        OrderReceived::class => [
            SendDepositEmail::class,
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
