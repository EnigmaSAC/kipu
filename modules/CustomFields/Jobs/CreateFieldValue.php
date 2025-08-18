<?php

namespace Modules\CustomFields\Jobs;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldCreate;
use Illuminate\Support\Facades\DB;
use Modules\CustomFields\Models\FieldValue;

class CreateFieldValue extends Job implements ShouldCreate
{
    public function handle()
    {
        DB::transaction(function () {
            $this->model = FieldValue::create($this->request->all());
        });

        return $this->model;
    }
}
