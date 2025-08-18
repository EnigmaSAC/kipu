<?php

namespace Modules\CustomFields\Jobs;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldUpdate;
use Illuminate\Support\Facades\DB;

class UpdateFieldValue extends Job implements ShouldUpdate
{
    public function handle()
    {
        DB::transaction(function () {
            $this->model->update($this->request->all());
        });

        return $this->model;
    }
}
