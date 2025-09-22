<?php

namespace Snawbar\Guardian;

use Illuminate\Support\ServiceProvider;
use Snawbar\Guardian\Components\Guardian;
use Snawbar\Guardian\Middleware\GuardianMiddleware;

class GuardianServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('guardian', function () {
            return new Guardian();
        });
    }

    public function boot()
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../config/guardian.php' => config_path('guardian.php'),
        ], 'guardian-config');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'guardian');

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/guardian'),
        ], 'guardian-views');

        // Register middleware
        $this->app['router']->aliasMiddleware('guardian', GuardianMiddleware::class);
    }
}