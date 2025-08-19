<?php

namespace Modules\Estimates\Observers;

use App\Abstracts\Observer;
use Modules\Estimates\Models\Estimate;

class Document extends Observer
{
    public function deleted(Estimate $estimate): void
    {
        $estimate->extra_param()->delete();
    }

    public function created(Estimate $estimate): void
    {
        if ($estimate->created_from !== 'estimates::import') {
            return;
        }

        $estimate->extra_param()->create([
            'company_id' => company_id(),
            'expire_at' => $estimate->due_at,
        ]);
    }
}
