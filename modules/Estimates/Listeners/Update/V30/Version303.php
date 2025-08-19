<?php

namespace Modules\Estimates\Listeners\Update\V30;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Jobs\Setting\UpdateEmailTemplate;
use App\Models\Common\Company;
use App\Models\Module\Module;
use App\Models\Setting\EmailTemplate;
use App\Traits\Jobs;
use App\Traits\Permissions;
use Illuminate\Support\Facades\Log;

class Version303 extends Listener
{
    use Permissions;
    use Jobs;

    const ALIAS = 'estimates';

    const VERSION = '3.0.3';

    /**
     * Handle the event.
     *
     * @param  $event
     *
     * @return void
     */
    public function handle(Event $event): void
    {
        if ($this->skipThisUpdate($event)) {
            Log::channel('stderr')->info('Skipping the Estimates 3.0.3 update...');

            return;
        }

        Log::channel('stderr')->info('Starting the Estimates 3.0.3 update...');

        $this->updateCompanies();

        Log::channel('stderr')->info('Estimates 3.0.3 update finished.');
    }

    public function updateCompanies()
    {
        Log::channel('stderr')->info('Updating companies...');

        $current_company_id = company_id();

        $company_ids = Module::allCompanies()->alias(static::ALIAS)->pluck('company_id');

        foreach ($company_ids as $company_id) {
            Log::channel('stderr')->info('Updating company: ' . $company_id);

            $company = Company::find($company_id);

            if (!$company instanceof Company) {
                continue;
            }

            $company->makeCurrent();

            $this->updateEmailTemplates();

            Log::channel('stderr')->info('Company updated: ' . $company_id);
        }

        company($current_company_id)->makeCurrent();

        Log::channel('stderr')->info('Companies updated.');
    }

    public function updateEmailTemplates()
    {
        Log::channel('stderr')->info('Updating email templates...');

        $email_templates = [
            'estimate_view_admin',
            'estimate_approved_admin',
            'estimate_refused_admin',
        ];

        foreach ($email_templates as $email_template) {
            $this->updateEmailTemplate(alias: $email_template);
        }

        Log::channel('stderr')->info('Email templates updated.');
    }

    private function updateEmailTemplate(string $alias): void
    {
        $model = EmailTemplate::alias($alias)
                              ->where(
                                  'subject',
                                  "estimates::email_templates.$alias.subject"
                              )
                              ->orWhere(
                                  'body',
                                  "estimates::email_templates.$alias.body"
                              )
                              ->first();

        if (!empty($model)) {
            $this->dispatch(
                new UpdateEmailTemplate(
                    $model, [
                        'subject' => trans("estimates::email_templates.$alias.subject"),
                        'body'    => trans("estimates::email_templates.$alias.body"),
                    ]
                )
            );
        }
    }
}
