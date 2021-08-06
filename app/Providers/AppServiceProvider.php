<?php

namespace App\Providers;

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
        if(config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        setlocale(LC_ALL, "de_DE.UTF-8");
        \Illuminate\Support\Carbon::setLocale(config('app.locale'));

        // \Illuminate\Support\Facades\DB::listen(function($query) {
        //     \Illuminate\Support\Facades\File::append(
        //         storage_path('/logs/query.log'),
        //         $query->sql . ' [' . implode(', ', $query->bindings) . ']' . PHP_EOL
        //    );
        // });
    }
}
