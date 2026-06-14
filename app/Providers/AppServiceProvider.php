<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
        Paginator::useBootstrapFive();

        // Force HTTPS automatically in three scenarios (in priority order):
        //
        // 1. Running on Railway — Railway injects RAILWAY_ENVIRONMENT automatically
        //    into every container, no manual config needed.
        // 2. HTTPS_ONLY=true is set (manual override for other platforms).
        // 3. APP_URL already starts with https:// (e.g., correctly configured).
        //
        // TrustProxies(*) in bootstrap/app.php ensures request()->isSecure()
        // also works correctly behind Railway's load balancer.
        $onRailway  = ! empty(env('RAILWAY_ENVIRONMENT'));
        $httpsOnly  = (bool) env('HTTPS_ONLY', false);
        $urlIsHttps = str_starts_with(config('app.url'), 'https://');

        if ($onRailway || $httpsOnly || $urlIsHttps) {
            URL::forceScheme('https');
        }
    }
}
