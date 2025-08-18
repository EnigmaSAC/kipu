<?php

namespace Modules\CustomFields\Traits;

use App\Models\Module\Module;
use App\Traits\Cloud;
use App\Traits\DateTime;
use App\Traits\Modules;
use App\Utilities\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Modules\CustomFields\Models\Field;

trait CustomFields
{
    use DateTime, Modules, Cloud;

    /**
     * Can view composer work ?
     */
    public function canCompose(): bool
    {
        return ! app()->runningInConsole() || env('APP_INSTALLED') || $this->moduleIsEnabled('custom-fields');
    }

    public function getLocations(bool $translate = false, bool $types_out = false): array
    {
        $locations = [];

        $config = config('custom-fields');

        foreach ($config as $model) {
            if (isset($model['export'])) {
                unset($model['export']);
            }

            foreach ($model as $item) {
                if (isset($item['location'])) {
                    $location = $item['location'];
                    $location_code = $location['code'] ?? null;

                    if (isset($location['types'])) {
                        if (! $types_out) {
                            $locations[$location_code] = [];
                        }

                        foreach ($location['types'] as $types_location) {
                            $types_location_code = $types_location['code'] ?? null;

                            if ($translate) {
                                $types_location_name = trans_choice($types_location['name'], 2) ?? null;
                            } else {
                                $types_location_name = $types_location['name'] ?? null;
                            }

                            if ($types_location_code && $types_location_name) {
                                if (! $types_out) {
                                    $locations[$location_code] += [$types_location_code => $types_location_name];
                                } else {
                                    $locations[$types_location_code] = $types_location_name;
                                }
                            }
                        }

                        continue;
                    }

                    if ($translate) {
                        $location_name = trans_choice($location['name'], 2) ?? null;
                    } else {
                        $location_name = $location['name'] ?? null;
                    }

                    if ($location_code && $location_name) {
                        $locations[$location_code] = $location_name;
                    }
                }
            }
        }

        return $locations;
    }

    /**
     * Check if can compose.
     */
    public function findCodeByViewName($view_name)
    {
        $code = null;

        $config = config('custom-fields');

        foreach ($config as $key => $items) {
            foreach ($items as $item) {
                if (! is_array($item)) {
                    continue;
                }

                foreach ($item['views'] as $view_key => $views) {
                    if (in_array($view_name, $views)) {
                        $code = $item['location']['code'];

                        break;
                    }
                }

                if (! empty($code)) {
                    break;
                }

                if (empty($item['show_types'])) {
                    continue;
                }

                foreach ($item['show_types'] as $type => $show_types) {
                    if (in_array($view_name, $show_types)) {
                        $code = $item['location']['code'];

                        break;
                    }
                }

                if (! empty($code)) {
                    break;
                }
            }

            if (! empty($code)) {
                break;
            }
        }

        return $code;
    }

    public function getFieldsByLocation($code, $type = null, $relations = false)
    {
        if (empty($code = $this->codeIsExists($code, $type, true))) {
            return collect([]);
        }

        $function   = 'byLocation';
        $func_value = $code;

        if ($relations) {
            foreach (data_get(config('custom-fields'), '*.*') as $value) {
                if (isset($value['location']['code']) && $value['location']['code'] == $code && isset($value['relations'])) {
                    $function   = 'byLocations';
                    $func_value = array_merge([$func_value], array_keys($value['relations']));

                    break;
                }
            }
        }

        return Field::with('fieldTypeOption')
            ->enabled()
            ->orderBy('name')
            ->$function($func_value)
            ->get();
    }

    /**
     * Get config value by location code.
     */
    public function getConfigValueByLocation(string $location): array
    {
        $config = config('custom-fields');

        foreach ($config as $model) {
            if (isset($model['export'])) {
                unset($model['export']);
            }

            foreach ($model as $item) {
                if (isset($item['location'])) {
                    $location_config = $item['location'];

                    if ($location_config['code'] == $location) {
                        return $item;
                    }
                }
            }
        }

        return [];
    }

    public function hasMultipleOptions(Field $field): bool
    {
        if ($field->fieldTypeOption->count() == 1) {
            return false;
        }

        return true;
    }

    /**
     * Get the exportable fields for the given model.
     */
    public function getExportableFields(string $class): mixed
    {
        $location = $this->getExportLocations();

        if (! array_key_exists($class, $location)) {
            return collect([]);
        }

        if (is_array($code = $this->getLocations()[$location[$class]])) {
            $fields = Field::with('fieldTypeOption')
            ->enabled()
            ->orderBy('name')
            ->whereIn('location', array_keys($code))
            ->get();
        } else {
            $fields = $this->getFieldsByLocation($location[$class]);
        }

        $fields = $fields->reject(function ($field) use ($class) {
            if (in_array($class, ['App\Exports\Sales\Sheets\InvoiceItems', 'App\Exports\Purchases\Sheets\BillItems'])) {
                return $field->sort != 'item_custom_fields';
            } elseif (in_array($class, ['App\Exports\Sales\Sheets\Invoices', 'App\Exports\Purchases\Sheets\Bills'])) {
                return $field->sort == 'item_custom_fields';
            }
        });

        return $fields;
    }

    public function getExportLocations(): array
    {
        foreach (config('custom-fields') ?? [] as $custom_field) {
            if (isset($custom_field['export'])) {
                if (is_array($custom_field['export'])) {
                    foreach ($custom_field['export'] as $value) {
                        $location[$value] = $custom_field[0]['location']['code'];
                    }
                } else {
                    $location[$custom_field['export']] = $custom_field[0]['location']['code'];
                }
            } else {
                if (isset($custom_field[1])) {
                    foreach ($custom_field as $model) {
                        if (isset($model['export']) === false) {
                            continue;
                        }

                        if (is_array($model['export'])) {
                            foreach ($model['export'] as $value) {
                                $location[$value] = $model['location']['code'];
                            }
                        } else {
                            $location[$model['export']] = $model['location']['code'];
                        }
                    }
                }
            }
        }

        return $location ?? [];
    }

    public function getRulesByType(): array
    {
        $types = config('custom-fields-types');

        $rules = config('custom-fields-rules.validation');

        foreach ($types as $type) {
            foreach ($type['rules'] as $rule) {
                if (in_array($rule, array_keys($rules))) {
                    $rules_by_type[$type['code']][$rule] = trans($rules[$rule]);
                }
            }
        }

        return $rules_by_type;
    }

    /**
     * It gets default value if field has not multiple options.
     */
    public function getDefaultValue(Field $field): string|null
    {
        if (!$this->hasMultipleOptions($field)) {
            return $field->fieldTypeOption->first()->value;
        }

        return null;
    }

    public function getSortOrders(): array
    {
        $sort_orders = [];

        $config = config('custom-fields');

        $configs = collect($config);

        $configs->flatten(1)
            ->each(function ($item) use (&$sort_orders) {
                $orders = $item['sort_orders'] ?? null;
                $location_code = $item['location']['code'] ?? null;

                if (! is_null($orders)) {
                    foreach ($orders as $name => $order) {
                        if (is_array($order)) {
                            $orders[$name] = trans_choice($order[0], $order[1]);
                        } else {
                            $orders[$name] = trans($order);
                        }
                    }
                }

                if ($location_code && $orders) {
                    if (isset($item['location']['types'])) {
                        foreach ($item['location']['types'] as $types_location) {
                            $sort_orders[$types_location['code']] = $orders;
                        }
                    } else {
                        $sort_orders[$location_code] = $orders;
                    }

                    Field::byLocation($location_code)
                        ->get(['name', 'code'])
                        ->each(function ($field) use (&$sort_orders, $location_code) {
                            $sort_orders[$location_code][$field->code] = $field->name;
                        });
                }
            });

        return $sort_orders;
    }

    public function getTypes($translate = false): array
    {
        $types = [];

        $config = config('custom-fields-types');

        $configs = collect($config);

        if ($translate) {
            $configs->transform(function ($item) {
                $item['name'] = trans($item['name']);

                return $item;
            });
        }

        $types = $configs->pluck('name', 'code')->toArray();

        return $types;
    }

    /**
     * Return bool or code, if there is a code in the location.
     */
    public function codeIsExists(string $code = '', ?string $type = '', bool $return = false): bool|string
    {
        $locations = $this->getLocations();

        if (! isset($locations[$code])) {
            $code = $this->checkLocationReference($code);
        }

        if (isset($locations[$code]) && is_array($locations[$code]) && ! empty($type)) {
            foreach (array_keys($locations[$code]) as $new_code) {
                if (str($new_code)->contains($type)) {
                    $code = $new_code;
                }
            }
        }

        return $return ? $code : (empty($code) ? true : false);
    }

    /**
     * It checks if the code is in the diffrent location reference. If it is, it returns the location code.
     */
    public function checkLocationReference(string $code): string|array
    {
        $data = data_get(config('custom-fields'), '*.*');

        foreach ($data as $item) {
            if (is_array($item) && isset($item['location_reference'])) {
                if (in_array($code, $item['location_reference'])) {
                    return $item['location']['code'];
                }
            }

            if (is_array($item) && isset($item['location']['types'])) {
                foreach ($item['location']['types'] as $type) {
                    if ($code == $type['code']) {
                        return $type['code'];
                    }
                }
            }
        }

        return '';
    }

    /**
     * If there is a custom fields config file in modular, it will fetch it with the data in it.
     */
    public function getCustomFieldsConfigInModules(): array
    {
        $company_id = $this->app['request']->segment(1);

        $cache_key = str_replace('{company_id}', $company_id, env('CUSTOM_FIELDS_CONFIG_IN_MODULES', 'companies.{company_id}.modules.installed'));

        if ($this->isCloud()) {
            $modules = Cache::get($cache_key);

            if (empty($modules)) {
                $modules = Module::where('company_id', $company_id)->enabled()->pluck('alias')->toArray();
            }
        } else {
            $modules = Module::where('company_id', $company_id)->enabled()->pluck('alias')->toArray();
        }

        Cache::put($cache_key, $modules, Date::now()->addHour());

        $config_data = [];

        foreach ($modules as $module) {
            $path = module_path($module, 'Config/custom-fields.php');

            if (File::exists($path)) {
                $config_data[] = require $path;
            }
        }

        return Arr::collapse($config_data);
    }
}
