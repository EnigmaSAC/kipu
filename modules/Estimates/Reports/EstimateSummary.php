<?php

namespace Modules\Estimates\Reports;

use App\Abstracts\Report;
use App\Models\Common\Report as Model;
use Modules\Estimates\Models\Estimate;

class EstimateSummary extends Report
{
    public $default_name = 'estimates::general.estimate_summary';

    public $icon = 'check';

    public $type = 'summary';

    public $chart = [
        'bar' => [
            'colors' => [
                '#8bb475',
            ],
        ],
        'donut' => [
            //
        ],
    ];

    public function setData()
    {
        //@TODO change here with the new daily update commit
        switch ($this->getSearchStringValue('basis', $this->getSetting('basis'))) {
            case 'invoiced':
                $this->setTotals(Estimate::estimate()->invoiced()->get(), 'issued_at');

                break;
            default:
                $estimates = $this->applyFilters(Estimate::estimate()->accrued(), ['date_field' => 'issued_at'])->get();
                $this->setTotals($estimates, 'issued_at');
                break;
        }
    }

    public function getBasisField()
    {
        return [
            'type' => 'selectGroup',
            'name' => 'basis',
            'title' => trans('general.basis'),
            'icon' => 'file',
            'values' => [
                'accrual' => trans('general.accrual'),
                'invoiced' => trans('estimates::general.messages.status.invoiced'),
            ],
            'selected' => 'accrual',
            'attributes' => [
                'required' => 'required',
            ],
        ];
    }
}
