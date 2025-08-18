<?php

namespace Modules\CustomFields\Http\ViewComposers;

use Illuminate\Support\Str;
use Illuminate\View\View;
use Modules\CustomFields\Models\Field as ModelsField;
use Modules\CustomFields\Traits\CustomFields;

class Field
{
    use CustomFields;

    public $code;
    public $request;

    public function __construct()
    {
        $this->request = request();

        $code = $this->codeIsExists(
            $this->request->segment(2) . '-' . $this->request->segment(3),
            $this->request?->type,
            true
        );

        $this->code = $code; 
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if (! $this->canCompose()) {
            return;
        }

        $view_name = $view->getName();

        if (empty($this->code) || $this->code == '-') {
            $this->code = $this->findCodeByViewName($view_name);
        }

        $type = $this->request->type;

        // its will be triggered from events
        if (in_array($this->code, ['banking-transactions', 'portal-payments'])) {
            if ($view_name == 'modals.documents.payment') {
                $document = \App\Models\Document\Document::find($this->request->segment(4));

                $document_type = config('type.document.' . $document->type, null);

                $type = $document_type ? $document_type['transaction_type'] : null;
            } else {
                $type = \App\Models\Banking\Transaction::find($this->request->segment(4))?->type;
            }

            if (empty($type)) {
                $transaction = isset($view->getData()['transaction']) ? $view->getData()['transaction'] : null;
                $document = isset($view->getData()['document']) ? $view->getData()['document'] : null;

                if (! empty($document)) {
                    $document_type = config('type.document.' . $document->type, null);

                    $type = $document_type ? $document_type['transaction_type'] : null;
                } else {
                    $type = $transaction ? $transaction->type : null;
                }
            }
        }

        $fields = $this->getFieldsByLocation($this->code, $type);

        if ($fields->isEmpty()) {
            return;
        }

        foreach ($fields as $field) {
            $attributes = $this->getInputAttributes($field);

            $values = $this->getInputValues($field, $this->request);

            $options = $field->fieldTypeOption->pluck('value', 'id')->toArray();

            if ($field->sort == 'item_custom_fields') {
                $attributes = $this->getInputAttributesForItems($field);

                $values = $this->getInputValuesForItems($field, $this->request);

                $view->getFactory()->startPush('scripts', view('custom-fields::partials.script', compact('field', 'values')));

                $values = null;
            }

            $label = mb_convert_case($field->name, MB_CASE_TITLE, "UTF-8");

            $component_name = Str::ucfirst($field->type);

            $component = "App\View\Components\Form\Group\\$component_name";

            $width_options = [
                '16'    => 'sm:col-span-1',
                '33'    => 'sm:col-span-2',
                '50'    => 'sm:col-span-3',
                '100'   => 'sm:col-span-6',
            ];

            $class = $width_options[$field->width];

            $component = new $component(name:$field->code, label:$label, value:$values, formGroupClass:$class, options:$options, selected:$values, checked:$values, required:$attributes['required']);

            $component->withAttributes($attributes);

            $key = $component->data();

            $content = $component->render()->with($key);

            $view->getFactory()->startPush($field->sort, $content);
        }
    }

    public function getInputAttributes(ModelsField $field): array
    {
        switch ($field->type) {
            case 'date':
                $attributes = [
                    'v-model' => 'form.' . $field->code,
                    'show-date-format' => $this->getCompanyDateFormat(),
                ];

                break;

            case 'dateTime':
                $attributes = [
                    'v-model' => 'form.' . $field->code,
                    'show-date-format' => $this->getCompanyDateFormat() . ' H:i',
                ];

                break;

            case 'time':
            case 'text':
            case 'textarea':

                $attributes = [
                    'v-model' => 'form.' . $field->code,
                    'placeholder' => $this->getDefaultValue($field),
                ];

                break;

            case 'select':
                $attributes = [
                    'v-model' => 'form.' . $field->code,
                    'model' => 'this.' . $field->code,
                ];

                break;

            default:
                $attributes = [];

                break;
        }

        $attributes['required'] = Str::contains($field->rule, 'required');

        if ($field->type == 'textarea') {
            $attributes['rows'] = '3';
        }

        return $attributes;
    }

    public function getInputAttributesForItems(ModelsField $field): array
    {
        switch ($field->type) {
            case 'text':
            case 'textarea':
                $attributes = [
                    'data-item' => $field->code,
                    'v-model' => null,
                    'v-bind:value' => 'row.' . $field->code . ' = this.' . $field->code . '[index]',
                    '@input' => "row.$field->code = \$event.target.value; this.$field->code[index] = \$event.target.value; onBindingItemField(index, '$field->code')",
                    'placeholder' => $this->getDefaultValue($field),
                ];

                break;

            case 'date':
            case 'time':
            case 'dateTime':
                $attributes = [
                    'data-item' => $field->code,
                    'v-model' => 'row.' . $field->code,
                    'change' => "onBindingItemField(index, '$field->code')",
                    'model' => 'this.' . $field->code . '[index]',
                    'show-date-format' => $this->getCompanyDateFormat(),
                ];

                break;

            case 'select':
                $attributes = [
                    'data-item' => $field->code,
                    'v-model' => 'row.' . $field->code,
                    'visible-change' => "onBindingItemField(index, '$field->code')",
                    'model' => 'this.' . $field->code . '[index]',
                ];

                break;

            case 'checkbox':
                $attributes = [
                    ':id' => '\'checkbox-' . $field->code . '-:item_id\'',
                    'data-item' => $field->code,
                    '@change' => "onBindingItemField(index, '$field->code')",
                    'v-model' => 'row.' . $field->code,
                ];

                break;

            default:
                $attributes = [];

                break;
        }

        $attributes['required'] = Str::contains($field->rule, 'required');

        if ($field->type == 'textarea') {
            $attributes['rows'] = '3';
        }

        return $attributes;
    }

    public function getInputValues($field, $request)
    {
        $value = $field->default;

        if ($field->type == 'checkbox') {
            $value = [$field->default];
        }

        // $model will be injected from route parameters
        $number = 1;
        $model = null;

        do {
            $segment = Str::replace('-', '_', Str::singular((string) $request->segment($number)));

            if (empty($segment)) {
                break;
            }

            $model = $request->route($segment);
            $number++;
        } while (is_null($model));

        if (is_null($model)) {
            return $value;
        }

        // getting recorded value of field
        $field_value = $field->field_values()->record($model)->first();

        if (!is_null($field_value) && !empty($field_value->value)) {
            $value = $field_value->value;
        }

        return $value;
    }

    public function getInputValuesForItems($field, $request): array
    {
        $default_value = null;
        $values = [];

        if (! $request->routeIs('invoices.edit') &&
            ! $request->routeIs('bills.edit') &&
            ! $request->routeIs('expenses.expense-claims.edit')) {
            return $values;
        }

        $default_value = $this->getDefaultValue($field);

        $document = $request->route(Str::replace('-', '_', Str::singular((string) $request->segment(3))));

        foreach ($document->items as $item) {
            $field_value = $field->field_values()->record($item)->first();

            if (is_null($field_value) || empty($field_value->value)) {
                $values[] = $default_value;

                continue;
            }

            $values[] = $field_value->value;
        }

        return $values;
    }
}
