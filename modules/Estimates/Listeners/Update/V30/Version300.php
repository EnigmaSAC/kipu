<?php

namespace Modules\Estimates\Listeners\Update\V30;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Jobs\Setting\CreateEmailTemplate;
use App\Models\Common\Company;
use App\Models\Common\Widget;
use App\Models\Module\Module;
use App\Traits\Jobs;
use App\Traits\Permissions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Modules\Estimates\Notifications\Estimate;
use Modules\Estimates\Widgets\EstimatesByCategory;
use Modules\Estimates\Widgets\LatestEstimates;
use Modules\Estimates\Widgets\TotalEstimates;

class Version300 extends Listener
{
    use Permissions;
    use Jobs;

    const ALIAS = 'estimates';

    const VERSION = '3.0.0';

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
            return;
        }

        Log::channel('stderr')->info('Starting the Estimates 3.0 update...');

        $this->updateDatabase();
        $this->deleteOldWidgets();
        $this->updateCompanies();
        $this->updatePermissions();
        $this->deleteOldFiles();

        Log::channel('stderr')->info('Estimates 3.0 update finished.');
    }

    protected function updateDatabase()
    {
        Log::channel('stderr')->info('Updating database...');

        DB::table('migrations')->insertOrIgnore(
            [
                'id'        => DB::table('migrations')->max('id') + 1,
                'migration' => '2020_01_10_000000_estimates_v2',
                'batch'     => DB::table('migrations')->max('batch') + 1,
            ]
        );

        Artisan::call('module:migrate', ['alias' => self::ALIAS, '--force' => true]);

        Log::channel('stderr')->info('Database updated.');
    }

    public function updateCompanies()
    {
        Log::channel('stderr')->info('Updating companies...');

        $current_company_id = company_id();

        $company_ids = Module::allCompanies()->alias(static::ALIAS)->pluck('company_id');

        foreach ($company_ids as $company_id) {
            Log::channel('stderr')->info('Updating company: ' . $company_id);

            $company = Company::find($company_id);

            if (! $company instanceof Company) {
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
        Log::channel('stderr')->info('Creating new email templates...');

        $this->dispatch(
            new CreateEmailTemplate([
                'company_id'   => company_id(),
                'alias'        => 'estimate_view_admin',
                'class'        => Estimate::class,
                'name'         => 'estimates::settings.estimate.view_admin',
                'subject'      => trans('estimates::email_templates.estimates_view_admin.subject'),
                'body'         => trans('estimates::email_templates.estimates_view_admin.body'),
                'created_from' => 'core::seed',
            ])
        );

        Log::channel('stderr')->info('Email templates updated/created.');
    }

    public function deleteOldWidgets()
    {
        Log::channel('stderr')->info('Deleting old widgets...');

        // Delete old widgets
        $old_widgets = [
            EstimatesByCategory::class,
            LatestEstimates::class,
            TotalEstimates::class,
        ];

        DB::transaction(function () use ($old_widgets) {
            DB::table('widgets')->whereIn('class', $old_widgets)->delete();
        });

        Log::channel('stderr')->info('Old widgets deleted.');
    }

    public function updatePermissions()
    {
        Log::channel('stderr')->info('Updating permissions...');

        $rows = [
            'accountant' => [
                'estimates-estimates' => 'r',
            ],
        ];

        Log::channel('stderr')->info('Attaching new permissions...');

        // c=create, r=read, u=update, d=delete
        $this->attachPermissionsByRoleNames($rows);

        // c=create, r=read, u=update, d=delete
        $this->attachPermissionsToAdminRoles([
            'estimates-settings-estimate' => 'r,u',
        ]);

        Log::channel('stderr')->info('Permissions updated.');
    }

    protected function deleteOldFiles()
    {
        $files = [
            'Http/Controllers/Modals/EstimateTemplate.php',
            'Http/ViewComposers/IndexExpiryDateFieldHeader.php',
            'Http/ViewComposers/ShowConvertToSalesOrder.php',
            'Http/ViewComposers/ShowExpiryDateFieldHeader.php',
            'Resources/views/fields/index_expiry_date_header.blade.php',
            'Resources/views/fields/show_convert_to_sales_order.blade.php',
            'Resources/views/fields/show_expiry_date_header.blade.php',
            'Resources/views/partials/customer/estimates_count.blade.php',
            'Database/migrations/2020_01_10_000000_estimates_v200.php',
            'Database/migrations/2021_01_14_000000_estimates_v210.php',
            'Database/migrations/2021_01_21_000000_estimates_v211.php',
        ];

        $directories = [
            'Resources/views/modals/settings',
        ];

        foreach ($files as $file) {
            File::delete(base_path('modules/Estimates/'.$file));
        }

        foreach ($directories as $directory) {
            File::deleteDirectory(base_path('modules/Estimates/'.$directory));
        }
    }
}
