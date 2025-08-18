<?php

namespace Modules\CustomFields\Listeners\Update\V30;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished;
use App\Models\Common\Company;
use App\Models\Module\Module;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\CustomFields\Models\FieldLocation;
use Modules\CustomFields\Models\Location;

class Version300 extends Listener
{
    const ALIAS = 'custom-fields';

    const VERSION = '3.0.0';

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

        $this->updateTypes();

        $this->updateLocations();

        $this->updateCompanies();

        $this->deleteOldFiles();
    }

    public function updateTypes()
    {
        DB::table('custom_fields_types')
            ->where('type', '=', 'radio')
            ->update([
                'name' => 'custom-fields::general.type.toggle',
                'type' => 'toggle',
            ]);
    }

    public function updateLocations()
    {
        DB::table('custom_fields_locations')
            ->where('name', 'general.revenues')
            ->update([
                'name' => 'general.incomes',
                'code' => 'banking.transactions',
            ]);

        DB::table('custom_fields_locations')
            ->where('name', 'general.payments')
            ->update([
                'name' => 'general.expenses',
                'code' => 'banking.transactions',
            ]);

        DB::table('custom_fields_locations')
            ->where('name', 'employees::general.positions')
            ->update([
                'name' => 'employees::general.departments',
                'code' => 'employees.departments',
            ]);
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

            $this->updateFieldLocations();

            Log::channel('stderr')->info('Company updated: ' . $company_id);
        }

        company($current_company_id)->makeCurrent();

        Log::channel('stderr')->info('Companies updated.');
    }

    public function updateFieldLocations()
    {
        FieldLocation::where('sort_order', 'like', 'position_id%')
            ->get()
            ->map(function ($location) {
                if (Str::contains($location->sort_order, 'position')) {
                    $location->sort_order = Str::replace($location->sort_order, 'position_id', 'department_id');
                }

                return $location;
            })
            ->each(function ($location) {
                $location->save();
            });

        $location = Location::code('employees.employees')->first();

        FieldLocation::where('sort_order', 'like', 'enabled%')
            ->where('location_id', '=', $location->id)
            ->get()
            ->map(function ($field_location) {
                $field_location->sort_order = Str::replace($field_location->sort_order, 'enabled', 'attachment');

                return $field_location;
            })
            ->each(function ($field_location) {
                $field_location->save();
            });

        $location = Location::code('employees.departments')->first();

        FieldLocation::where('sort_order', 'like', 'enabled%')
            ->where('location_id', '=', $location->id)
            ->get()
            ->map(function ($field_location) {
                $field_location->sort_order = Str::replace($field_location->sort_order, 'enabled', 'description');

                return $field_location;
            })
            ->each(function ($field_location) {
                $field_location->save();
            });

        $location = Location::code('common.companies')->first();

        FieldLocation::where('sort_order', 'like', 'enabled%')
            ->where('location_id', '=', $location->id)
            ->get()
            ->map(function ($field_location) {
                $field_location->sort_order = Str::replace($field_location->sort_order, 'enabled', 'phone');

                return $field_location;
            })
            ->each(function ($field_location) {
                $field_location->save();
            });

        $location = Location::code('common.items')->first();

        FieldLocation::where('sort_order', 'like', 'enabled%')
            ->where('location_id', '=', $location->id)
            ->get()
            ->map(function ($field_location) {
                $field_location->sort_order = Str::replace($field_location->sort_order, 'enabled', 'description');

                return $field_location;
            })
            ->each(function ($field_location) {
                $field_location->save();
            });

        $location = Location::code('sales.customers')->first();

        FieldLocation::where('sort_order', 'like', 'enabled%')
            ->where('location_id', '=', $location->id)
            ->get()
            ->map(function ($field_location) {
                $field_location->sort_order = Str::replace($field_location->sort_order, 'enabled', 'reference');

                return $field_location;
            })
            ->each(function ($field_location) {
                $field_location->save();
            });

        $location = Location::code('purchases.vendors')->first();

        FieldLocation::where('sort_order', 'like', 'enabled%')
            ->where('location_id', '=', $location->id)
            ->get()
            ->map(function ($field_location) {
                $field_location->sort_order = Str::replace($field_location->sort_order, 'enabled', 'reference');

                return $field_location;
            })
            ->each(function ($field_location) {
                $field_location->save();
            });

        $location = Location::code('banking.accounts')->first();

        FieldLocation::where('sort_order', 'like', 'enabled%')
            ->where('location_id', '=', $location->id)
            ->get()
            ->map(function ($field_location) {
                $field_location->sort_order = Str::replace($field_location->sort_order, 'enabled', 'default_account');

                return $field_location;
            })
            ->each(function ($field_location) {
                $field_location->save();
            });
    }

    public function deleteOldFiles()
    {
        $files = [
            'Resources/views/field.blade.php',
            'Listeners/Employees/PositionSaved.php',
            'Listeners/Employees/PositionDeleted.php',
        ];

        foreach ($files as $file) {
            File::delete(base_path('modules/CustomFields/' . $file));
        }
    }
}
