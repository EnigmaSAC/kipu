<?php

namespace Modules\CustomFields\Providers;

use Illuminate\Support\ServiceProvider;

class DynamicRelations extends ServiceProvider
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
        $model_names = array_keys(config('custom-fields'));

        foreach ($model_names as $model_name) {
            $model_name::resolveRelationUsing('custom_fields', function ($model) {
                return $model->morphMany('Modules\CustomFields\Models\FieldValue', 'model');
            });
        }
    }
}
