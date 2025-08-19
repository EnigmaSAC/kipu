<?php

namespace Modules\Estimates\Http\ViewComposers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\View\View;
use Modules\Estimates\Models\Estimate;

class AddEstimatesStatistics
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
        if (false === user()->can('read-estimates-estimates')) {
            return;
        }

        $limit     = request('limit', setting('default.list_limit', '25'));
        $viewData  = $view->getData();
        $customer  = $viewData['customer'];
        $estimates = Estimate::estimate()->with('transactions')->contact($customer->id)->get();

        if ($estimates->count() === 0) {
            return;
        }

        $estimates = $this->paginate($estimates->sortByDesc('issued_at'), $limit);

        $view->getFactory()->startPush(
            'documents_nav_start',
            view('estimates::partials.customer.estimates_tab')
        );

        $view->getFactory()->startPush(
            'documents_tab_start',
            view('estimates::partials.customer.estimates_content', ['documents' => $estimates])
        );
    }

    /**
     * Generate a pagination collection.
     *
     * @param array|Collection $items
     * @param int              $perPage
     * @param int              $page
     * @param array            $options
     *
     * @return LengthAwarePaginator
     */
    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $perPage = $perPage ?: (int)request('limit', setting('default.list_limit', '25'));

        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
