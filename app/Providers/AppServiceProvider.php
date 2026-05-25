<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Indonesian month/day names for translatedFormat() in certificate view
        Carbon::setLocale('id');

        // Force HTTPS in production
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Rate limiters for donor-facing routes
        RateLimiter::for('search', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip())
                ->response(fn () => response()->json(
                    ['message' => 'Terlalu banyak percobaan. Coba lagi dalam 1 menit.'], 429
                ));
        });

        RateLimiter::for('view', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });
    }
}
