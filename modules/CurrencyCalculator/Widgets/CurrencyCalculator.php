<?php

namespace Modules\CurrencyCalculator\Widgets;

use App\Abstracts\Widget;

class CurrencyCalculator extends Widget
{
    public $default_name = 'currency-calculator::general.name';

    public $description = 'currency-calculator::general.description';

    public function show()
    {
        return $this->view('currency-calculator::currency_calculator', [
                'condition' => \App\Models\Setting\Currency::count() > 1
        ]);
    }
}
