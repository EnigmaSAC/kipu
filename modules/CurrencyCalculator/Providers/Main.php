<?php

namespace Modules\CurrencyCalculator\Providers;

use Livewire\Livewire;
use Modules\CurrencyCalculator\Livewire\CurrencyCalculator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as Provider;

class Main extends Provider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViews();
        $this->loadLivewire();
        $this->loadTranslations();
    }

    /**
     * Load views.
     *
     * @return void
     */
    public function loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'currency-calculator');
    }

    /**
     * Load view components.
     *
     * @return void
     */
    public function loadLivewire()
    {
        Livewire::component('currency-calculator', CurrencyCalculator::class);
    }

    /**
     * Load translations.
     *
     * @return void
     */
    public function loadTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'currency-calculator');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
