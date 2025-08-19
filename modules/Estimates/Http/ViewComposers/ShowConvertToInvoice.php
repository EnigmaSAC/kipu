<?php

namespace Modules\Estimates\Http\ViewComposers;

use Illuminate\View\View;
use App\Traits\Modules;

class ShowConvertToInvoice
{
    use Modules;

    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $document = $view->getData()['estimate'];

        $description = trans_choice('general.statuses', 1) . ': ';
        if ($document->status !== 'invoiced') {
            $description .= trans('documents.statuses.not_invoiced');
        } else {
            $description .= trans('documents.statuses.invoiced');
        }

        $salesPurchaseOrdersEnabled = $this->moduleIsEnabled('sales-purchase-orders');

        $view->getFactory()->startPush(
            'get_paid_end',
            view('estimates::fields.show_convert_to_invoice', compact('document', 'description', 'salesPurchaseOrdersEnabled'))
        );
    }

}
