<?php

namespace Snawbar\Guardian;

use Illuminate\Support\ServiceProvider;
use Snawbar\Guardian\Components\Guardian;
use Snawbar\Guardian\Middleware\GuardianEnforcer;

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
        $this->registerTranslations();
        $this->publishConfig();
        $this->registerMiddlewares();
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

    private function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'snawbar-guardian');
    }

    private function publishConfig(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/guardian.php' => config_path('guardian.php'),
            ], 'snawbar-guardian-config');

            $this->publishes([
                __DIR__ . '/../views' => resource_path('views/vendor/snawbar-guardian'),
            ], 'snawbar-guardian-views');

            $this->publishes([
                __DIR__ . '/../lang' => lang_path('vendor/snawbar-guardian'),
            ], 'snawbar-guardian-lang');
        }
    }

    private function registerMiddlewares(): void
    {
        $this->app['router']->prependMiddlewareToGroup('web', GuardianEnforcer::class);
    }
}
