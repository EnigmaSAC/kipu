<?php

namespace Modules\CustomFields\Listeners;

use Illuminate\Support\Facades\DB;
use Modules\CustomFields\Models\FieldValue;

class CloneField
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($clone, $original)
    {
        $clone_field_value = FieldValue::record($clone)->first();

        if (! $clone_field_value) {
            return;
        }

        $original_field_value = FieldValue::record($original)->first();

        if ($clone_field_value->field->type != $original_field_value->field->type) {
            return;
        }

        DB::transaction(function () use ($clone_field_value, $original_field_value) {
            $clone_field_value->value = $original_field_value->value;
            $clone_field_value->save();
        });
    }
}
