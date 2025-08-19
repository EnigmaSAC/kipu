<?php

namespace Modules\DynamicDocumentNotes\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as Provider;

class Main extends Provider
{
    protected $config = [
        'dynamic-document-notes' => __DIR__ . '/../Config/config.php',
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViews();
        $this->loadTranslations();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->loadRoutes();
        $this->loadConfig();
    }

    /**
     * Load views.
     *
     * @return void
     */
    public function loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'dynamic-document-notes');
    }

    /**
     * Load translations.
     *
     * @return void
     */
    public function loadTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'dynamic-document-notes');
    }

    /**
     * Load routes.
     *
     * @return void
     */
    public function loadRoutes()
    {
        if (app()->routesAreCached()) {
            return;
        }

        $routes = [
            'admin.php',
        ];

        foreach ($routes as $route) {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/' . $route);
        }
    }

    /**
     * Load config.
     *
     * @return void
     */
    public function loadConfig()
    {
        foreach ($this->config as $path => $file) {
            $this->publishes([
                $file => config_path($path . '.php'),
            ], $path);

            $this->mergeConfigFrom($file, $path);
        }

        $replaceConfigs = [];

        foreach ($replaceConfigs as $config) {
            if (! Config::has($config)) {
                continue;
            }

            Config::set($config, array_merge_recursive(
                Config::get($config),
                require __DIR__ . '/../Config/' . $config . '.php'
            ));
        }
    }
}
