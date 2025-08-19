<?php

namespace Modules\AmountInWords\Http\ViewComposers;

use App\Traits\Modules;
use Illuminate\View\View;
use App\Models\Setting\Currency;
use NumberToWords\NumberToWords;

class Document
{
    use Modules;

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if (! $this->moduleIsEnabled('amount-in-words')) {
            return;
        }

        $data = $view->getData();

        try {
            if ($data['type'] == 'invoice' || $data['type'] == 'bill') {
                $document = $data['document'];

                $amount = money($document->amount, $document->currency_code, true);

                $currency_symbol = Currency::where('code', $document->currency_code)->value('symbol');
                $amount = str_replace($currency_symbol, '', $amount);

                $amount = str_replace('.', '', $amount);
                $amount = str_replace(',', '', $amount);

                $numberToWords = new NumberToWords();
                $currencyTransformer = $numberToWords->getCurrencyTransformer(strtolower(language()->getShortCode()));
                $words = $currencyTransformer->toWords((int) $amount, $document->currency_code);

                $view->getFactory()->startPush('grand_total_tr_end', view('amount-in-words::words', compact('words')));
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();

            return response()->json([
                'error' => $message,
                'redirect' => false,
                'success' => false,
                'data' => false,
            ]);
        }
    }
}
