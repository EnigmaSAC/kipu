<?php

namespace Modules\CustomFields\Http\ViewComposers;

use App\Utilities\Date;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Modules\CustomFields\Traits\CustomFields;

class FieldShow
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

        $fields = $this->getFieldsByLocation($this->code, $type ?? null, true);

        if ($fields->isEmpty()) {
            return;
        }

        foreach ($fields as $field) {
            $field_value = $this->getFieldValueByRequest($field, $this->request, $view);

            if ($view_name != 'components.documents.template.line-item' && $field->sort == 'item_custom_fields') {
                continue;
            }

            if ($view_name == 'components.documents.template.line-item' && $field->sort == 'item_custom_fields') {
                $show_type = 'items';
            } else {
                $config_value   = $this->getConfigValueByLocation($field->location);
                $show_types     = data_get($config_value, 'show_types', []);
                $show_type      = $this->getShowType($view_name, $show_types);
            }

            $template = '';
            if ($view_name == 'components.transfers.show.content') {
                $template = $view->offsetGet('template');
            }

            $sort = $field->sort;
            $content = view('custom-fields::field_show', compact('field', 'field_value', 'show_type', 'template'));

            // document items
            if ($view->getName() == 'components.documents.template.line-item' && $field->sort == 'item_custom_fields') {
                $sort = $field->sort . '_' . $view->offsetGet('item')->id;
            }

            $view->getFactory()->startPush($sort, $content);
        }
    }

    /**
     * Get show type by view name.
     */
    public function getShowType(string $view_name, array $show_types): string
    {
        foreach ($show_types as $type => $views) {
            if (in_array($view_name, $views)) {
                return $type;
            }
        }

        return 'default';
    }

    public function getFieldValueByRequest($field, $request, $view)
    {
        $value = null;

        // $model will be injected from route parameters
        $model = $request->route(Str::replace('-', '_', Str::singular((string) $request->segment(3))));

        // if set queue empty request so then get view object first value
        if (empty($model) && should_queue()) {
            $codes = explode('-', $this->code);
            $code  = Str::singular($codes[1]);

            $model = isset ($view->getData()[$code]) ? $view->getData()[$code] : null;
        }

        if (is_null($model)) {
            return $value;
        }

        if (is_int($model) || is_string($model)) {
            $model = $this->findModel($model);
        }

        $model = $this->checkIsRelation($field, $model);

        $value = $this->getDefaultValue($field);

        // getting recorded value of field
        $field_value = $field->field_values()->record($model)->first();

        if (isset($view->getData()['item']) && $view->getData()['item']) {
            $item_model = $view->getData()['item'];
            $field_value = $field->field_values()->record($item_model)->first();
        }

        if (!is_null($field_value) && !is_null($field_value->value)) {
            $value = $field_value->value;

            if ($this->hasMultipleOptions($field)) {
                $field_type_options = $field->fieldTypeOption->pluck('value', 'id');
            }

            if (!is_array($field_value->value) && isset($field_type_options[$field_value->value])) {
                $value = $field_type_options[$field_value->value];
            }

            if (is_array($field_value->value)) {
                $value = null;

                foreach ($field_value->value as $item) {
                    if (!isset($field_type_options[$item])) {
                        continue;
                    }

                    $value .= "{$field_type_options[$item]}, ";
                }

                if ($value != null) {
                    $value = rtrim($value, ", ");
                }
            }

            if ($field_value->field->type == 'radio' && $value == 0) {
                $value = trans('general.no');
            }

            if ($field_value->field->type == 'radio' && $value == 1) {
                $value = trans('general.yes');
            }
        }

        if ($field->type == 'date') {
            $value = company_date($value);
        }

        if ($field->type == 'dateTime') {
            $value = Date::parse($value)->format(company_date_format() . ' H:i');
        }

        return $value;
    }

    public function findModel(int|string $model): Model
    {
        $custom_fields = config('custom-fields');

        foreach ($custom_fields as $class => $values) {
            if (isset($values['export'])) {
                unset($values['export']);
            }

            foreach ($values as $value) {
                if ($value['location']['code'] == $this->code) {
                    $model = $class::find($model);
                }
            }
        }

        return $model;
    }

    public function checkIsRelation($field, $model)
    {
        $code = $this->codeIsExists($this->code, request()->type, true);

        if ($field->location != $code) {
            foreach (data_get(config('custom-fields'), '*.*') as $value) {
                if (isset($value['location']['code']) && $value['location']['code'] == $code && isset($value['relations'])) {
                    return $model->{$value['relations'][$field->location]};
                }
            }
        }

        return $model;
    }
}
