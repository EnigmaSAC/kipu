<?php

namespace Modules\CustomFields\Listeners\Update\V30;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished;
use App\Interfaces\Listener\ShouldUpdateAllCompanies;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class Version306 extends Listener implements ShouldUpdateAllCompanies
{
    const ALIAS = 'custom-fields';

    const VERSION = '3.0.6';

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

        $this->deleteOldFilesFolders();
    }

    public function updateDatabase()
    {
        Artisan::call('module:migrate', ['alias' => self::ALIAS, '--force' => true, '--subpath' => '2022_09_01_000000_custom_fields_v306.php']);
    }

    public function deleteOldFilesFolders()
    {
        $files = [
            'Models/FieldLocation.php',
            'Models/Type.php',
            'Casts/Translate.php',
        ];

        $directories = [
            'Casts',
            'Observers/Banking',
            'Observers/Common',
            'Observers/Document',
            'Listeners/Assets',
            'Listeners/Employees',
        ];

        foreach ($files as $file) {
            File::delete(base_path('modules/CustomFields/' . $file));
        }

        foreach ($directories as $directory) {
            File::deleteDirectory(base_path('modules/CustomFields/' . $directory));
        }
    }
}
