<?php

namespace Modules\CustomFields\Listeners\Export;

use App\Events\Export\RowsPreparing;
use Modules\CustomFields\Traits\CustomFields;

class AppendRows
{
    use CustomFields;

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(RowsPreparing $event)
    {
        $fields = $this->getExportableFields(get_class($event->class));

        if ($fields->isEmpty()) {
            return;
        }

        $export_fields = collect($event->class->fields);

        foreach ($fields as $field) {
            if (!$export_fields->contains($field->code)) {
                $event->class->fields[] = $field->code;
            }
        }

        foreach ($event->rows as $row) {
            foreach ($fields as $field) {
                $row->{$field->code} = $this->getFieldValueByModel($field, $row);
            }
        }
    }

    public function getFieldValueByModel($field, $model)
    {
        $value = null;

        if (is_null($model)) {
            return $value;
        }

        $value = $this->getDefaultValue($field);

        $field_value = $field->field_values()->record($model)->first();

        if (!is_null($field_value) && !empty($field_value->value)) {
            $value = $field_value->value;

            if ($this->hasMultipleOptions($field)) {
                $field_type_options = $field->fieldTypeOption->pluck('value', 'id');
            }

            if ($field_value->field->type != 'checkbox' && isset($field_type_options[$field_value->value])) {
                $value = $field_type_options[$field_value->value];
            }

            if ($field_value->field->type == 'checkbox') {
                $value = $field_type_options->flip()
                    ->intersect($field_value->value)
                    ->flip()
                    ->implode(', ');
            }
        }

        return $value;
    }
}
