<?php

namespace Modules\Estimates\Http\ViewComposers;

use Illuminate\View\View;

class ShowMarkAsApprovedRefused
{

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

        if ($document->status === 'approved') {
            $description .= trans('documents.statuses.approved');
        } elseif ($document->status === 'refused') {
            $description .= trans('documents.statuses.refused');
        } else {
            $description .= trans('estimates::general.messages.status.await_action');
        }

        $view->getFactory()->startPush(
            'get_paid_start',
            view('estimates::fields.show_mark_as_approved_refused', compact('document', 'description'))
        );
    }

}
