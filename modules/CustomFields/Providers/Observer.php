<?php

namespace Modules\CustomFields\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CustomFields\Traits\CustomFields;
use Modules\CustomFields\Observers\CustomFields as CustomFieldsObserver;

class Observer extends ServiceProvider
{
    use CustomFields;

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        $models = array_keys(config('custom-fields'));

        foreach ($models as $model) {
            $model::observe(CustomFieldsObserver::class);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
