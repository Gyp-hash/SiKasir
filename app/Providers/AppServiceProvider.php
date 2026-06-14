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

        // Force HTTPS scheme when APP_URL is configured as https://.
        // This ensures route() / url() / asset() always generate https:// links
        // when deployed to Railway, Render, Heroku, or any HTTPS platform.
        //
        // TrustProxies is configured in bootstrap/app.php (runs earlier in the
        // pipeline) so that request()->isSecure() also returns true correctly.
        //
        // To activate on Railway: set APP_URL=https://your-app.up.railway.app
        if (str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
