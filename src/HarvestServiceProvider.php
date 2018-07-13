<?php

namespace Djam90\Harvest;

use Illuminate\Support\ServiceProvider;

class HarvestServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/harvest.php' => config_path('harvest.php'),
        ], 'harvest');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/harvest.php', 'harvest');

        $this->app->singleton(
            'harvest',
            HarvestService::class
        );
    }

    public function provides()
    {
        return ['harvest'];
    }
}