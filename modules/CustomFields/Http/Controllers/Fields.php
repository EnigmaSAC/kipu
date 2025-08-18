<?php

namespace Modules\CustomFields\Http\Controllers;

use App\Abstracts\Http\Controller;
use Illuminate\Http\Response;
use Modules\CustomFields\Http\Requests\Field as Request;
use Modules\CustomFields\Jobs\CreateField;
use Modules\CustomFields\Jobs\DeleteField;
use Modules\CustomFields\Jobs\DuplicateField;
use Modules\CustomFields\Jobs\UpdateField;
use Modules\CustomFields\Models\Field;
use Modules\CustomFields\Traits\CustomFields;

class Fields extends Controller
{
    use CustomFields;

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $custom_fields = Field::collect();

        $types = $this->getTypes(true);

        $locations = $this->getLocations(true, true);

        return view('custom-fields::fields.index', compact('custom_fields', 'locations', 'types'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return $this->index();
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $types = $this->getTypes(true);

        $locations = $this->getLocations(true, true);

        $sort_orders = $this->getSortOrders();

        $orders = [
            'input_start' => trans('custom-fields::general.form.before'),
            'input_end' => trans('custom-fields::general.form.after'),
        ];

        $shows = [
            'always' => trans('custom-fields::general.form.shows.always'),
            'never' => trans('custom-fields::general.form.shows.never'),
            'if_filled' => trans('custom-fields::general.form.shows.if_filled'),
        ];

        $width_options = [
            '16' => '16%',
            '33' => '33%',
            '50' => '50%',
            '100' => '100%',
        ];

        $rules_by_type = $this->getRulesByType();

        return view('custom-fields::fields.create', compact('types', 'locations', 'orders', 'shows', 'rules_by_type', 'width_options', 'sort_orders'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $response = $this->ajaxDispatch(new CreateField($request));

        $response['redirect'] = route('custom-fields.fields.index');

        $message = $response['success']
                    ? trans('messages.success.added', ['type' => trans('custom-fields::general.name')])
                    : $response['message'];

        flash($message, $response['success'] ? 'success' : 'error');

        return response()->json($response);
    }

    /**
     * Duplicate the specified resource.
     *
     * @param  Field  $field
     *
     * @return Response
     */
    public function duplicate(Field $field)
    {
        $response = $this->ajaxDispatch(new DuplicateField($field));

        if ($response['success']) {
            flash(trans('messages.success.duplicated', ['type' => trans('custom-fields::general.name')]))->success();

            return redirect()->route('custom-fields.fields.edit', $response['data']->id);
        } else {
            flash($response['message'])->error()->important();

            return redirect()->route('custom-fields.fields.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param  Field $field
     * @return Response
     */
    public function edit(Field $field)
    {
        $types = $this->getTypes(true);

        $locations = $this->getLocations(true, true);

        $sort_orders = $this->getSortOrders();

        $orders = [
            'input_start' => trans('custom-fields::general.form.before'),
            'input_end' => trans('custom-fields::general.form.after'),
        ];

        $shows = [
            'always' => trans('custom-fields::general.form.shows.always'),
            'never' => trans('custom-fields::general.form.shows.never'),
            'if_filled' => trans('custom-fields::general.form.shows.if_filled'),
        ];

        $width_options = [
            '16' => '16%',
            '33' => '33%',
            '50' => '50%',
            '100' => '100%',
        ];

        $sort_values = $sort_orders[$field->location] ?? [];

        if (isset($sort_values[$field->code])) {
            unset($sort_values[$field->code]);
        }

        $sort_order = explode('_input_', $field->sort);

        $sort = $sort_order[0];

        if ($field->sort == 'item_custom_fields' || empty($field->sort) || ! str($field->sort)->contains('_input_')) {
            $order = null;
        } else {
            $order = 'input_' . $sort_order[1];
        }

        $field->sort = $sort;
        $field->order = $order;

        $view = 'type_option_value';

        if ($field->type == 'select' || $field->type == 'checkbox') {
            $view = 'type_option_values';
        }

        $custom_field_values = "";

        if ($field->fieldTypeOption) {
            $custom_field_values = $field->fieldTypeOption->pluck('value', 'id')->toArray();
        }

        $rules_by_type = $this->getRulesByType();

        $selected_validation_rules = null;

        if (! empty($field->rule)) {
            $selected_validation_rules = explode('|', $field->rule);
        }

        return view('custom-fields::fields.edit', compact('field', 'types', 'locations', 'orders', 'sort_values', 'shows', 'view', 'custom_field_values', 'rules_by_type', 'selected_validation_rules', 'width_options', 'sort_orders'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Field $field
     * @param  Request $request
     * @return Response
     */
    public function update(Field $field, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateField($field, $request));

        $response['redirect'] = route('custom-fields.fields.index');

        $message = $response['success']
                    ? trans('messages.success.updated', ['type' => trans('custom-fields::general.name')])
                    : $response['message'];

        flash($message, $response['success'] ? 'success' : 'error');

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(Field $field)
    {
        $response = $this->ajaxDispatch(new DeleteField($field));

        $response['redirect'] = route('custom-fields.fields.index');

        $message = $response['success']
                    ? trans('messages.success.deleted', ['type' => trans('custom-fields::general.name')])
                    : $response['message'];

        flash($message, $response['success'] ? 'success' : 'error');

        return response()->json($response);
    }
}
