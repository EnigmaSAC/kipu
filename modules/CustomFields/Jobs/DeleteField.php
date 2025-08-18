<?php

namespace Modules\CustomFields\Jobs;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldDelete;
use Illuminate\Support\Facades\DB;
use Modules\CustomFields\Models\FieldValue;

class DeleteField extends Job implements ShouldDelete
{
    public function handle()
    {
        DB::transaction(function () {
            $this->deleteRelationships($this->model, ['fieldTypeOption']);

            $field_values = FieldValue::where('field_id', $this->model->id)->get();

            foreach ($field_values as $field_value) {
                $field_value->delete();
            }

            $this->model->delete();
        });

        return true;
    }
}
