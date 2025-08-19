<?php

namespace Modules\CurrencyCalculator\Livewire;

use App\Models\Setting\Currency;
use Livewire\Component;

class CurrencyCalculator extends Component
{
    public $base_currency_rate;

    public $second_currency_rate;

    public $base_currency;

    public $second_currency;

    public $base_currency_data;

    public $second_currency_data;

    public $old_base_currency_rate;

    public $old_second_currency_rate;

    public $old_base_currency;

    public $old_second_currency;

    public $old_base_currency_data;

    public $old_second_currency_data;

    public $has_currency = true;

    public function mount()
    {
        $default = setting('default.currency', 'USD');
        $base = Currency::where('code', $default)->get()->toArray();
        $second = Currency::all()->whereNotIn('code', $default)->random(1)->toArray();

        if (empty($base)) {
            $this->has_currency = false;
            return;
        }

        $this->old_base_currency_rate = number_format($base[0]["rate"], 2, '.', '');
        $this->old_second_currency_rate = number_format($second[0]["rate"], 2, '.', '');
        $this->base_currency_data = $this->second_currency_data = Currency::enabled()->get(['id', 'name', 'rate'])->keyBy('id')->toArray();

        $this->fill([
            'base_currency_rate' => $this->old_base_currency_rate,
            'base_currency' => (string) $base[0]["id"],
            'second_currency' => (string) $second[0]["id"],
        ]);

        $this->base_currency_data = $this->searchAndRemove($this->second_currency, $this->base_currency_data, 'old_base_currency_data');
        $this->second_currency_data = $this->searchAndRemove($this->base_currency, $this->second_currency_data, 'old_second_currency_data');

        $this->old_base_currency = $this->base_currency;
        $this->old_second_currency = $this->second_currency;

        $this->fill(['second_currency_rate' => $this->old_second_currency_rate]);
    }

    public function render()
    {
        if (! $this->has_currency) {
            return view('currency-calculator::livewire.currency_calculator', [
                'has_currency' => $this->has_currency
            ]);
        }

        $this->checkSelectInputs();
        $this->checkRateInputs();

        return view('currency-calculator::livewire.currency_calculator', [
            'base_currency_data' => $this->base_currency_data,
            'second_currency_data' => $this->second_currency_data,
            'has_currency' => $this->has_currency
        ]);
    }

    public function checkRateInputs()
    {
        if ($this->base_currency_rate == null) {
            $this->base_currency_rate = $this->old_base_currency_rate;
            return;
        } elseif ($this->second_currency_rate == null) {
            $this->second_currency_rate = $this->old_second_currency_rate;
            return;
        }

        if ($this->base_currency_rate != $this->old_base_currency_rate) {
            $this->changeSecondRate();
            return;
        }

        if ($this->second_currency_rate != $this->old_second_currency_rate) {
            $this->changeBaseRate();
            return;
        }
    }

    public function checkSelectInputs()
    {
        if ($this->base_currency != $this->old_base_currency) {
            $this->old_base_currency = $this->base_currency;
            $this->base_currency_rate = '1.00';
            $this->changeSecondRate();
            $this->second_currency_data = $this->searchAndRemove($this->base_currency, $this->second_currency_data, 'old_second_currency_data');
            return;
        }

        if ($this->second_currency != $this->old_second_currency) {
            $this->old_second_currency = $this->second_currency;
            $this->second_currency_rate = '1.00';
            $this->changeBaseRate();
            $this->base_currency_data = $this->searchAndRemove($this->second_currency, $this->base_currency_data, 'old_base_currency_data');
            return;
        }
    }

    public function changeBaseRate() {
        if ($this->second_currency_data[$this->second_currency]['rate'] == 1) {
            $this->base_currency_rate = (string) number_format($this->second_currency_rate * $this->base_currency_data[$this->base_currency]['rate'], 2, '.', '');
        } else {
            $this->base_currency_rate = (string) number_format($this->second_currency_rate * ($this->base_currency_data[$this->base_currency]['rate'] * (1 / $this->second_currency_data[$this->second_currency]['rate'])), 2, '.', '');
        }

        $this->old_base_currency_rate = $this->base_currency_rate;
        $this->old_second_currency_rate = $this->second_currency_rate;
    }

    public function changeSecondRate() {
        if ($this->base_currency_data[$this->base_currency]['rate'] == 1) {
            $this->second_currency_rate = (string) number_format($this->base_currency_rate * $this->second_currency_data[$this->second_currency]['rate'], 2, '.', '');
        } else {
            $this->second_currency_rate = (string) number_format($this->base_currency_rate * ($this->second_currency_data[$this->second_currency]['rate'] * (1 / $this->base_currency_data[$this->base_currency]['rate'])), 2, '.', '');
        }

        $this->old_base_currency_rate = $this->base_currency_rate;
        $this->old_second_currency_rate = $this->second_currency_rate;
    }

    public function searchAndRemove($id, $array, $old_data) {
        foreach ($array as $key => $value) {
            if ($id == $value['id']) {
                unset($array[$key]);

                if (isset($this->$old_data)) {
                    $array[$this->$old_data['id']] = $this->$old_data;
                }

                $this->$old_data = $value;
            }
        }

        return $array;
    }
}
