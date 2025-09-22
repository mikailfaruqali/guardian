<?php

namespace Snawbar\Guardian;

use Illuminate\Support\ServiceProvider;
use Snawbar\Guardian\Components\Guardian;
use Snawbar\Guardian\Middleware\GuardianMiddleware;

class GuardianServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerGuardianSingleton();
    }

    public function boot(): void
    {
        $this->registerRoutes();
        $this->registerViews();
        $this->publishConfig();
        $this->prependMiddlewareToWebGroup();
    }

    private function registerGuardianSingleton(): void
    {
        $this->app->singleton('guardian', fn () => new Guardian);
    }

    private function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    private function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../views', 'snawbar-guardian');
    }

    private function publishConfig(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/guardian.php' => config_path('snawbar-guardian.php'),
                __DIR__ . '/../views' => resource_path('views/vendor/snawbar-guardian'),
                __DIR__ . '/../lang' => lang_path('snawbar-guardian'),
            ], 'snawbar-guardians');
        }
    }

    private function prependMiddlewareToWebGroup(): void
    {
        $this->app['router']->prependMiddlewareToGroup('web', GuardianMiddleware::class);
    }
}
