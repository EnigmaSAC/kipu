<?php

namespace Modules\Estimates\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use Modules\Estimates\Models\Estimate as Model;
use Illuminate\Support\Facades\URL;

class EstimateShare extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create-estimates-estimates')->only('create', 'store');
    }

    public function create(Model $estimate)
    {
        $signedUrl = URL::signedRoute('signed.estimates.estimates.show', [$estimate->id]);
        $previewUrl = route('preview.estimates.estimates.show', [$estimate->id]);

        $html = view('estimates::modals.share', compact('estimate', 'signedUrl', 'previewUrl'))->render();

        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
            'data' => [
                'title' => trans('general.title.new', ['type' => trans('general.share_link')]),
                'success_message' => trans('invoices.share.success_message'),
                'buttons' => [
                    'cancel' => [
                        'text' => trans('general.cancel'),
                        'class' => 'btn-outline-secondary',
                    ],
                    'confirm' => [
                        'text' => trans('general.copy_link'),
                        'class' => 'disabled:bg-green-100',
                    ],
                ]
            ]
        ]);
    }
}
