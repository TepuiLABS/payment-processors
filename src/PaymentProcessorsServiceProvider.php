<?php

namespace Tepuilabs\PaymentProcessors;

use Illuminate\Support\ServiceProvider;

class PaymentProcessorsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/payment-processors.php' => config_path('payment-processors.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../resources/views' => base_path('resources/views/vendor/payment-processors'),
            ], 'views');
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'payment-processors');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/payment-processors.php', 'payment-processors');
    }
}
