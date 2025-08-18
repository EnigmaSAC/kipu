<?php

namespace Modules\CustomFields\Listeners;

use App\Events\Common\SearchStringApplied;

class RemoveCustomFieldsSearchString
{
    /**
     * Handle the event.
     *
     * @param  SearchStringApplied $event
     *
     * @return void
     */
    public function handle(SearchStringApplied $event)
    {
        $request = request();

        if (is_null($search = $request->get('search')) || !session()->has('custom_fields_search_string_replaced')) {
            return;
        }

        $search = str_replace(session('custom_fields_search_string_replaced'), '', $search);

        session()->forget('custom_fields_search_string_replaced');

        $request->merge(['search' => $search]);
    }
}
