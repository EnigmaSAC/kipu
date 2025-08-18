<?php

namespace Modules\CustomFields\Observers;

use App\Abstracts\Observer;
use App\Traits\Jobs;
use Modules\CustomFields\Jobs\CreateFieldValue;
use Modules\CustomFields\Jobs\DeleteFieldValue;
use Modules\CustomFields\Jobs\UpdateFieldValue;
use Modules\CustomFields\Models\Field;
use Modules\CustomFields\Models\FieldValue;
use Modules\CustomFields\Traits\CustomFields as CustomFieldsTrait;

class CustomFields extends Observer
{
    use CustomFieldsTrait, Jobs;

    protected function getCode($model)
    {
        $config_model = config('custom-fields.' . get_class($model));

        if (isset($config_model[1])) {
            if (isset($config_model['export'])) {
                unset($config_model['export']);
            }

            foreach ($config_model as $item) {
                if ($item['type'] === $model->type) {
                    return $item['location']['code'];
                }
            }
        } else {
            return $config_model[0]['location']['code'];
        }
    }

    /**
     * Listen to the created event.
     *
     * @param $model
     *
     * @return void
     */
    public function created($model)
    {
        if (is_null($code = $this->getCode($model))) {
            return;
        }

        $custom_fields = $this->getFieldsByLocation($code, $model->type ?? null);

        foreach ($custom_fields as $custom_field) {
            $value = request($custom_field->code);

            if (isset($model->allAttributes[$custom_field->code])) {
                $value = $model->allAttributes[$custom_field->code];
            }

            $this->dispatch(new CreateFieldValue([
                'company_id' => company_id(),
                'field_id' => $custom_field->id,
                'model_id' => $model->id,
                'model_type' => get_class($model),
                'value' => $value,
            ]));
        }
    }

    public function saved($model)
    {
        $method = request()->getMethod();

        if ($method == 'PATCH') {
            $this->updated($model);
        }
    }

    /**
     * Listen to the created event.
     *
     * @param $model
     *
     * @return void
     */
    public function updated($model)
    {
        if (is_null($code = $this->getCode($model))) {
            return;
        }

        $skips = [];
        $custom_fields = $this->getFieldsByLocation($code, $model->type ?? null);
        $custom_field_values = FieldValue::record($model)->get();

        if ($custom_field_values) {
            foreach ($custom_field_values as $custom_field_value) {
                $custom_field = Field::find($custom_field_value->field_id);

                if (empty($custom_field)) {
                    continue;
                }

                if (! request()->has($custom_field->code)) {
                    continue;
                }

                $value = request($custom_field->code);

                if (isset($model->allAttributes[$custom_field->code])) {
                    $value = $model->allAttributes[$custom_field->code];
                }

                $this->dispatch(new UpdateFieldValue($custom_field_value, [
                    'company_id' => company_id(),
                    'field_id' => $custom_field->id,
                    'model_id' => $model->id,
                    'model_type' => get_class($model),
                    'value' => $value,
                ]));

                $skips[] = $custom_field->id;
            }
        }

        if ($custom_fields) {
            foreach ($custom_fields as $custom_field) {
                if (in_array($custom_field->id, $skips)) {
                    continue;
                }

                if (! request()->has($custom_field->code)) {
                    continue;
                }

                $value = request($custom_field->code);

                if (isset($model->allAttributes[$custom_field->code])) {
                    $value = $model->allAttributes[$custom_field->code];
                }

                $custom_field_value = $this->dispatch(new CreateFieldValue([
                    'company_id' => company_id(),
                    'field_id' => $custom_field->id,
                    'model_id' => $model->id,
                    'model_type' => get_class($model),
                    'value' => $value,
                ]));
            }
        }
    }

    /**
     * Listen to the deleted event.
     *
     * @param $model
     *
     * @return void
     */
    public function deleted($model)
    {
        $this->dispatch(new DeleteFieldValue($model));
    }
}
