<?php

namespace Modules\CustomFields\Listeners\Update\V30;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished;
use App\Interfaces\Listener\ShouldUpdateAllCompanies;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\CustomFields\Models\FieldLocation;

class Version305 extends Listener implements ShouldUpdateAllCompanies
{
    const ALIAS = 'custom-fields';

    const VERSION = '3.0.5';

    /**
     * Handle the event.
     *
     * @param  $event
     *
     * @return void
     */
    public function handle(UpdateFinished $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->updateDatabase();

        $this->updateLocations();

        $this->updateTypes();

        $this->updateSorts();

        $this->deleteOldFilesFolders();
    }

    public function updateDatabase()
    {
        Artisan::call('module:migrate', ['alias' => self::ALIAS, '--force' => true, '--subpath' => '2022_08_02_000000_custom_fields_v305.php']);
    }

    public function updateLocations()
    {
        $mapping = [
            'general.companies' => 'common-companies',
            'general.items' => 'common-items',
            'general.invoices' => 'sales-invoices',
            'general.incomes' => 'transactions-incomes',
            'general.customers' => 'sales-customers',
            'general.bills' => 'purchases-bills',
            'general.expenses' => 'transactions-expenses',
            'general.vendors' => 'purchases-vendors',
            'general.accounts' => 'banking-accounts',
            'general.transfers' => 'banking-transfers',
            'expenses::general.expense_claims' => 'expenses-expense-claims',
            'employees::general.employees' => 'employees-employees',
            'employees::general.departments' => 'employees-departments',
            'CRM Contacts' => 'crm-contacts',
            'CRM Companies' => 'crm-companies',
            'estimates::general.estimates' => 'estimates-estimates',
            'assets::general.assets' => 'assets-assets',
        ];

        $locations = DB::table('custom_fields_locations')->get();

        foreach ($locations as $location) {
            DB::table('custom_fields_fields')
                ->where('location', "$location->id")
                ->update(['location' => $mapping[$location->name]]);
        }
    }

    public function updateTypes()
    {
        $types = DB::table('custom_fields_types')->get();

        foreach ($types as $type) {
            DB::table('custom_fields_fields')
                ->where('type_id', $type->id)
                ->update(['type' => $type->type]);
        }
    }

    public function updateSorts()
    {
        FieldLocation::all()
            ->each(function ($field_location) {
                $field = $field_location->field;

                if ($field) {
                    $field->sort = $field_location->sort_order;

                    $field->save();
                }
            });
    }

    public function deleteOldFilesFolders()
    {
        $files = [
            'Models/Location.php',
            'Database/Seeds/Locations.php',
            'Database/Seeds/Types.php',
            'Traits/LocationSortOrder.php',
            'Casts/TranslateChoice.php',
        ];

        foreach ($files as $file) {
            File::delete(base_path('modules/CustomFields/' . $file));
        }
    }
}
