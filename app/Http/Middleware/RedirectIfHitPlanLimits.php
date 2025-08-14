<?php

namespace App\Http\Middleware;

use App\Traits\Plans;
use Closure;
use Illuminate\Support\Facades\Log;

class RedirectIfHitPlanLimits
{
    use Plans;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $segments = $request->segments();
        $last_segment = end($segments);

        if (! $request->isMethod(strtolower('GET')) || ! in_array($last_segment, ['create'])) {
            return $next($request);
        }

        if ($request->ajax()) {
            return $next($request);
        }

        if ($request->is(company_id() . '/apps/*')) {
            return $next($request);
        }

        $user_limit = $this->getUserLimitOfPlan();
        if (! $user_limit->action_status) {
            return redirect()->route('users.index');
        }
        if (! $user_limit->view_status) {
            Log::warning($user_limit->message);
        }

        $company_limit = $this->getCompanyLimitOfPlan();
        if (! $company_limit->action_status) {
            return redirect()->route('companies.index');
        }
        if (! $company_limit->view_status) {
            Log::warning($company_limit->message);
        }

        $invoice_limit = $this->getInvoiceLimitOfPlan();
        if (! $invoice_limit->action_status) {
            return redirect()->route('invoices.index');
        }
        if (! $invoice_limit->view_status) {
            Log::warning($invoice_limit->message);
        }

        return $next($request);
    }
}
