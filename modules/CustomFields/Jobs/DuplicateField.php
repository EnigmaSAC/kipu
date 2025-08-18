<?php

namespace Modules\CustomFields\Jobs;

use App\Abstracts\Job;
use Illuminate\Support\Facades\DB;
use Modules\CustomFields\Models\Field;

class DuplicateField extends Job
{
    protected $clone;

    public function __construct(Field $model)
    {
        $this->model = $model;

        parent::__construct($model);
    }

    public function handle(): Field
    {
        DB::transaction(function () {
            $this->clone = $this->model->duplicate();
        });

        return $this->clone;
    }
}
