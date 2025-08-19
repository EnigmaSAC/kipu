<?php

namespace Modules\DynamicDocumentNotes\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Models\Banking\Account;
use App\Traits\Modules;
use App\Utilities\Date;
use Modules\DynamicDocumentNotes\Http\Requests\Document as Request;
use Illuminate\Support\Str;

class Documents extends Controller
{
    use Modules;

    public function note(Request $request)
    {
        $account = Account::find($request->get('account_id'));

        $template = $this->getTemplate($account);

        $breaks = array("<br />","<br>","<br/>");  
        $template = str_ireplace($breaks, "\r\n", $template);  

        $data['notes'] = $template;

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'data' => $data,
        ]);
    }

    protected function getTemplate($account)
    {
        $template = setting('dynamic-document-notes.template', config('dynamic-document-notes.default'));

        $fields = config('dynamic-document-notes.fields');

        // Core fields replace
        foreach ($fields as $field) {
            $key = str_replace(['{', '}'], '', $field);

            switch($key) {
                case 'currency_code':
                    $template = str_replace($field, $account->currency->name, $template);
                    break;
                default:
                    $template = str_replace($field, $account->{$key}, $template);
                    break;
            }
        }

        if ($this->moduleIsEnabled('custom-fields')) {
            $custom_fields = new \Modules\DynamicDocumentNotes\Utilities\CustomFields();

            $fields = $custom_fields->getFields([]);

            // Custom fields replace
            foreach ($fields as $field) {
                $key = str_replace(['{', '}'], '', $field);

                $custom_field = \Modules\CustomFields\Models\Field::where('name', Str::headline($key))->first();

                $template = str_replace($field, $this->getCustomFieldValue($custom_field, $account), $template);
            }
        }

        return strip_tags($template, ['<br>', '\r', '\n', '\t']);
    }

    protected function getCustomFieldValue($field, $model)
    {
        $custom_fields = new \Modules\DynamicDocumentNotes\Utilities\CustomFields();

        // getting recorded value of field
        $field_value = $field->field_values()->record($model)->first();

        $value = $custom_fields->getDefaultValue($field);

        if (! is_null($field_value) && ! is_null($field_value->value)) {
            $value = $field_value->value;

            if ($custom_fields->hasMultipleOptions($field)) {
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
}
