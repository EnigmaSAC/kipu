<?php

namespace Modules\CustomFields\Jobs;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldDelete;
use Illuminate\Support\Facades\DB;
use Modules\CustomFields\Models\FieldValue;

class DeleteFieldValue extends Job implements ShouldDelete
{
    public function handle()
    {
        DB::transaction(function () {
            FieldValue::record($this->model)->delete();
        });

        return true;
    }
}
