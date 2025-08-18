<?php

namespace Modules\CustomFields\Providers;

use App\Traits\Modules;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Validator;
use Modules\CustomFields\Traits\CustomFields;

class Validation extends ServiceProvider
{
    use CustomFields, Modules;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['validator']->resolver(function ($translator, $data, $rules, $messages, $attributes) {
            if (! in_array($this->app['request']->getMethod(), ['POST', 'PUT', 'PATCH'])) {
                return new Validator($translator, $data, $rules, $messages, $attributes);
            }

            $code = $this->codeIsExists(
                $this->app['request']->segment(2) . '-' . $this->app['request']->segment(3),
                $this->app['request']->type,
                true
            );

            if (empty($code) || $this->moduleIsDisabled('custom-fields') || isset($rules['import'])) {
                return new Validator($translator, $data, $rules, $messages, $attributes);
            }

            $custom_fields = $this->getFieldsByLocation($code);

            if ($custom_fields->isEmpty()) {
                return new Validator($translator, $data, $rules, $messages, $attributes);
            }

            $is_import = str($this->app['request']->route()->getName())->contains('import');

            $with = '';
            if ($is_import) {
                $with = '*.';
            }

            foreach ($custom_fields as $custom_field) {
                $rule = ! empty($custom_field->rule) ? $custom_field->rule : '';

                if ($is_import) {
                    $data_names = ! empty($data) ? $data[array_key_first($data)] : [];

                    if (! array_key_exists($custom_field->code, $data_names)) {
                        continue;
                    }
                }

                if (! request()->has('items') && $custom_field->sort === 'item_custom_fields') {
                    $rules['items.*.' . $custom_field->code] = $rule;
                }

                $rules[$with . $custom_field->code] = $rule;
                $attributes[$with . $custom_field->code] = $custom_field->name;
            }

            return new Validator($translator, $data, $rules, $messages, $attributes);
        });
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
