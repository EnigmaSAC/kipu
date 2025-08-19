<?php

namespace Modules\DynamicDocumentNotes\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Traits\Modules;
use Illuminate\Http\Response;
use Modules\DynamicDocumentNotes\Http\Requests\Setting as Request;

class Settings extends Controller
{
    use Modules;

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit()
    {
        $fields = config('dynamic-document-notes.fields');

        // convert language keys to values
        /*
        if (! is_null($fields)) {
            foreach ($fields as $name => $order) {
                if (is_array($order)) {
                    $fields[$name] = trans_choice($order[0], $order[1]);
                } else {
                    $fields[$name] = trans($order);
                }
            }
        }
        */

        if ($this->moduleIsEnabled('custom-fields')) {
            $custom_fields = new \Modules\DynamicDocumentNotes\Utilities\CustomFields();

            $fields = $custom_fields->getFields($fields);
        }

        return view('dynamic-document-notes::edit', compact('fields'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function update(Request $request)
    {
        $fields = $request->all();

        foreach ($fields as $key => $value) {
            if ($key == '_token' || $key == 'company_id' || $key == '_method' || $key == '_prefix') {
                continue;
            }

            setting()->set('dynamic-document-notes.' . $key, $value);
        }

        setting()->save();

        flash(trans('messages.success.updated', ['type' => trans_choice('general.settings', 2)]))->success();

        return response()->json([
            'success' => true,
            'error' => false,
            'redirect' => route('dynamic-document-notes.settings.edit'),
        ]);
    }
}