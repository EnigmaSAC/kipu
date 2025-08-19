<?php

namespace Modules\Estimates\Http\Controllers\Modals;

use App\Abstracts\Http\Controller;
use App\Jobs\Document\SendDocumentAsCustomMail;
use App\Http\Requests\Common\CustomMail as Request;
use Illuminate\Http\JsonResponse;
use Modules\Estimates\Models\Estimate as Model;
use Modules\Estimates\Notifications\Estimate as Notification;

class EstimateEmails extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create-estimates-estimates')->only('create', 'store');
    }

    public function create(Model $estimate): JsonResponse
    {
        $notification = new Notification($estimate, 'estimate_new_customer', true);

        $html = view('estimates::modals.email', compact('estimate', 'notification'))->render();

        return response()->json([
            'success' => true,
            'error'   => false,
            'message' => 'null',
            'html'    => $html,
            'data'    => [
                'title'   => trans('general.title.new', ['type' => trans_choice('general.email', 1)]),
                'buttons' => [
                    'cancel'  => [
                        'text'  => trans('general.cancel'),
                        'class' => 'btn-outline-secondary',
                    ],
                    'confirm' => [
                        'text'  => trans('general.send'),
                        'class' => 'disabled:bg-green-100',
                    ],
                ],
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $response = $this->ajaxDispatch(new SendDocumentAsCustomMail($request, 'estimate_new_customer'));

        if ($response['success']) {
            $estimate = Model::find($request->get('document_id'));

            $response['redirect'] = route('estimates.estimates.show', $estimate->id);

            $message = trans(
                'documents.messages.email_sent',
                ['type' => setting('estimates.estimate.title', trans_choice('estimates::general.estimates', 1))]
            );

            flash($message)->success();
        } else {
            $response['redirect'] = null;
        }

        return response()->json($response);
    }
}
