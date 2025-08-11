<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

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
        Paginator::useBootstrap();

        RateLimiter::for('email-sending', function () {
            // Ограничение отправки писем в минуту
            return Limit::perMinute(20);
        });

        RateLimiter::for('email-sending-hourly', function () {
            // Ограничение отправки писем в час
            return Limit::perHour(1500);
        });
    }
}
