<?php

namespace Modules\Estimates\Jobs;

use App\Abstracts\Http\FormRequest;
use App\Abstracts\Job;
use App\Utilities\Date;
use Modules\Estimates\Models\Estimate;

class UpdateEstimateStatus extends Job
{
    protected $document;

    protected $request;


    public function __construct(Estimate $document, FormRequest $request)
    {
        parent::__construct($document, $request);

        $this->document = $document;
        $this->request  = $this->getRequestInstance($request);
    }


    public function handle(): void
    {
        if ($this->document->extra_param->expire_at <= Date::now()) {
            return;
        }

        $this->document->update(['status' => 'draft']);
    }
}
