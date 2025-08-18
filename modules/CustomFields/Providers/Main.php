<?php

namespace Modules\CustomFields\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CustomFields\Traits\CustomFields;

class Main extends ServiceProvider
{
    use CustomFields;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViews();
        $this->loadMigrations();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->loadTranslations();
        $this->loadConfig();
        $this->loadRoutes();
    }

    /**
     * Load config.
     *
     * @return void
     */
    public function loadConfig()
    {
        /**
         * It was written to save the custom-fields files in the modules at once,
         * since the providers in the modules proceed according to the alphabet.
         */
        // config(['custom-fields' => $this->getCustomFieldsConfigInModules()]);

        $config_keys = ['custom-fields', 'custom-fields-types', 'custom-fields-rules', 'search-string'];

        foreach ($config_keys as $key) {
            $config_file = require __DIR__ . "/../Config/{$key}.php";

            if ($key === 'search-string') {
                $config_file = $this->updateSearchStrings($config_file);
            }

            if (! empty($config = config($key))) {
                $value = array_merge_recursive(
                    $config,
                    $config_file
                );
            } else {
                $value = $config_file;
            }

            config([$key => $value]);
        }
    }

    /**
     * load views.
     *
     * @return void
     */
    public function loadViews()
    {
        $viewPath = resource_path('views/modules/custom-fields');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath,
        ]);

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/custom-fields';
        }, config('view.paths')), [$sourcePath]), 'custom-fields');
    }

    /**
     * load translations.
     *
     * @return void
     */
    public function loadTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'custom-fields');
    }

    public function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Load routes.
     *
     * @return void
     */
    public function loadRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin.php');
    }

    public function updateSearchStrings($config_file)
    {
        $config_file['Modules\CustomFields\Models\Field']['columns']['location']['values'] = $this->getLocations();

        $config_file['Modules\CustomFields\Models\Field']['columns']['type']['values'] = $this->getTypes();
        $config_file['Modules\CustomFields\Models\Field']['columns']['type']['translation'] = trans_choice('general.types', 1);

        $models = array_keys(config('custom-fields'));

        foreach ($models as $model) {
            $config_file[$model]['columns']['custom_fields']['relationship'] = true;
        }

        return $config_file;
    }
}
