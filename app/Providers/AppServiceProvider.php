<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(config('app.env') === 'packageion') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        setlocale(LC_ALL, "de_DE.UTF-8");
        \Illuminate\Support\Carbon::setLocale(config('app.locale'));

        View::share('filters', [
            'state' => Request::query('state'),
            'from' => Request::query('from'),
            'days' => Request::query('days'),
            'venue' => Request::query('venue'),
            'room' => Request::query('room'),
        ]);

        // \Illuminate\Support\Facades\DB::listen(function($query) {
        //     \Illuminate\Support\Facades\File::append(
        //         storage_path('/logs/query.log'),
        //         $query->sql . ' [' . implode(', ', $query->bindings) . ']' . PHP_EOL
        //    );
        // });
    }
}
