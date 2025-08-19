<?php

namespace Modules\DynamicDocumentNotes\Http\Requests;

use App\Abstracts\Http\FormRequest as Request;

class Document extends Request
{
    public function rules()
    {
        return [

            'account_id'  => 'required|integer',

        ];
    }
}
