<?php

namespace Modules\DynamicDocumentNotes\Utilities;

use Modules\CustomFields\Traits\CustomFields as CustomFieldsTrait;
use Illuminate\Support\Str;

class CustomFields
{
    use CustomFieldsTrait;

    public function getFields($fields)
    {
        $code = 'banking-accounts';

        $custom_fields = self::getFieldsByLocation($code);

        if ($custom_fields->isEmpty()) {
            return $fields;
        }

        foreach ($custom_fields as $custom_field) {
            $fields[] = '{' . Str::slug($custom_field->name, '_') . '}';
        }

        return $fields;
    }

    public function getFieldValue($field, $account)
    {
        $custom_field = self::getCustomFieldByCode($field);

        if (empty($custom_field)) {
            return $account->{$field};
        }

        $value = $account->customFields()->where('custom_field_id', $custom_field->id)->first();

        if (empty($value)) {
            return '';
        }

        return $value->value;
    }
}
