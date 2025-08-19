<?php

namespace Modules\Estimates\Http\ViewComposers;

use App\Traits\DateTime;
use App\Utilities\Date;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class ShowSentTimeline
{

    use DateTime;

    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $date_format = $this->getCompanyDateFormat();
        $document    = $view->getData()['estimate'];

        $description = trans_choice('general.statuses', 1) . ': ';
        if ($document->status === 'viewed') {
            $description .= trans('invoices.messages.status.viewed');
        } elseif ($document->status === 'sent') {
            $description .= trans('invoices.messages.status.send.sent', ['date' => Date::parse($document->sent_at)->format($date_format)]);
        }

        $view->getFactory()->startPush(
            'send_start',
            view('estimates::fields.show_sent_timeline', compact('date_format', 'document', 'description'))
        );
    }

}
