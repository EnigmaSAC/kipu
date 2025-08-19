<?php

namespace Modules\Estimates\Listeners\Update\V30;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Jobs\Setting\CreateEmailTemplate;
use App\Models\Common\Company;
use App\Models\Module\Module;
use App\Models\Setting\EmailTemplate;
use App\Traits\Jobs;
use App\Traits\Permissions;
use Illuminate\Support\Facades\Log;
use Modules\Estimates\Notifications\Estimate;

class Version3011 extends Listener
{
    use Permissions;
    use Jobs;

    const ALIAS = 'estimates';

    const VERSION = '3.0.11';

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
            Log::channel('stderr')->info('Skipping the Estimates 3.0.11 update...');

            return;
        }

        Log::channel('stderr')->info('Starting the Estimates 3.0.11 update...');

        $this->updateCompanies();

        Log::channel('stderr')->info('Estimates 3.0.11 update finished.');
    }

    public function updateCompanies(): void
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

            $this->createEmailTemplates();

            Log::channel('stderr')->info('Company updated: ' . $company_id);
        }

        company($current_company_id)->makeCurrent();

        Log::channel('stderr')->info('Companies updated.');
    }

    public function createEmailTemplates(): void
    {
        Log::channel('stderr')->info('Creating email templates...');

        $email_templates = [
            [
                'alias' => 'estimate_new_customer',
                'name'  => 'estimates::settings.estimate.new_customer',
            ],
            [
                'alias' => 'estimate_remind_customer',
                'name'  => 'estimates::settings.estimate.remind_customer',
            ],
            [
                'alias' => 'estimate_remind_admin',
                'name'  => 'estimates::settings.estimate.remind_admin',
            ],
            [
                'alias' => 'estimate_view_admin',
                'name' => 'estimates::settings.estimate.view_admin',
            ],
            [
                'alias' => 'estimate_approved_admin',
                'name'  => 'estimates::settings.estimate.approved_admin',
            ],
            [
                'alias' => 'estimate_refused_admin',
                'name'  => 'estimates::settings.estimate.refused_admin',
            ]
        ];

        foreach ($email_templates as $email_template) {
            $model = EmailTemplate::alias($email_template['alias'])->first();
            if (!empty($model)) {
                continue;
            }

            $this->dispatch(new CreateEmailTemplate([
                'company_id' => company_id(),
                'alias' => $email_template['alias'],
                'class' => Estimate::class,
                'name' => $email_template['name'],
                'subject' => trans("estimates::email_templates.{$email_template['alias']}.subject"),
                'body' => trans("estimates::email_templates.{$email_template['alias']}.body")
            ]));
        }

        Log::channel('stderr')->info('Email templates created.');
    }
}

