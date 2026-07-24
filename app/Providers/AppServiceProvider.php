<?php

namespace App\Providers;

use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RedirectIfAuthenticated::redirectUsing(function () {
            return '/dashboard';
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(100)->by($request->user()?->tenant_id ?: $request->ip());
        });
    }
}
