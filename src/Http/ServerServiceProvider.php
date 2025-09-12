<?php
namespace LicenseServer;

use Illuminate\Support\ServiceProvider;

class ServerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'license-server');
        $this->publishes([
            __DIR__ . '/../config/license.php' => config_path('license.php'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/license.php',
            'license-server'
        );
    }
}
