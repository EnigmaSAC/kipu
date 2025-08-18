<?php

namespace Modules\CustomFields\Listeners\Update\V30;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished;
use Illuminate\Support\Facades\File;
use Modules\CustomFields\Models\Field;
use Modules\CustomFields\Models\FieldLocation;
use Modules\CustomFields\Models\FieldValue;
use Modules\CustomFields\Models\Location;

class Version302 extends Listener
{
    const ALIAS = 'custom-fields';

    const VERSION = '3.0.2';

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

        $this->updateLocations();

        $this->deleteOldFiles();
    }

    public function updateLocations()
    {
        Location::all()->countBy(function ($location) {
            return $location->name;
        })->reject(function ($value) {
            return $value == 1;
        })->each(function ($item, $key) {
            $locations = Location::where('name', $key)->get();

            if ($locations->count() >= 2) {
                $base_location = $locations->shift();

                $ids = $locations->pluck('id');

                Field::whereIn('locations', $ids)
                    ->update(['locations' => $base_location->id]);

                FieldLocation::whereIn('location_id', $ids)
                    ->update(['location_id' => $base_location->id]);

                FieldValue::whereIn('location_id', $ids)
                    ->update(['location_id' => $base_location->id]);

                Location::destroy($ids);
            }
        });
    }

    public function deleteOldFiles()
    {
        $files = [
            'Listeners/Update/Version200.php',
            'Listeners/Update/Version210.php',
            'Listeners/Update/Version213.php',
            'Listeners/Update/Version214.php',
            'Listeners/Update/Version215.php',
            'Listeners/Update/Version2110.php',
            'Listeners/Update/Version2112.php',
            'Listeners/Update/Version2113.php',
            'Listeners/Update/Version300.php',
        ];

        foreach ($files as $file) {
            File::delete(base_path('modules/CustomFields/' . $file));
        }
    }
}
