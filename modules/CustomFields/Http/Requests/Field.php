<?php

namespace Modules\CustomFields\Http\Requests;

use App\Abstracts\Http\FormRequest;

class Field extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'      => 'required|string',
            'width'     => 'nullable|string',
            'type'      => 'required|string',
            'location'  => 'required|string',
            'sort'      => 'required|string',
            'enabled'   => 'integer|boolean',
        ];

        $type = $this->input('type');

        if ($type == 'select' || $type == 'checkbox') {
            $rules['items']     = 'required|array';
            $rules['items.*']   = 'required|array';
        }

        if ($this->input('sort') != 'item_custom_fields') {
            $rules['order']     = 'required|string';
        }

        if ($this->filled('default') && $this->filled('rule')) {
            $rules['default']   = implode('|', $this->input('rule'));
        }

        return $rules;
    }
}
