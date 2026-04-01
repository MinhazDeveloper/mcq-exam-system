<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 'gemini_api' limit create per minute 4 for free users based on IP address
        RateLimiter::for('gemini_api', function (Request $request) { 
            // Per minute 4 requests allowed for free users
            // The user is identified by their IP address
            return Limit::perMinute(4)->by($request->ip())->response(function (Request $request, array $headers) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Too many requests. Please wait a minute and try again.'
                ], 429);
            });
        });
    }
}
