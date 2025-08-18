<?php

namespace Modules\CustomFields\Listeners\Update\V30;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Version310 extends Listener
{
    const ALIAS = 'custom-fields';

    const VERSION = '3.1.0';

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

        $this->updateMigration();

        $this->updateColumn();

        $this->deleteOldFilesFolders();
    }

    public function updateMigration()
    {
        Artisan::call('module:migrate', ['alias' => self::ALIAS, '--force' => true, '--subpath' => '2022_10_27_074247_custom_fields_v310.php']);
    }

    public function updateColumn()
    {
        $width_options = [
            'sm:col-span-1' => '16',
            'sm:col-span-2' => '33',
            'sm:col-span-3' => '50',
            'sm:col-span-6' => '100',
        ];

        foreach ($width_options as $class => $width) {
            DB::table('custom_fields_fields')
                ->where('width', $class)
                ->update(['width' => $width]);
        }
    }

    public function deleteOldFilesFolders()
    {
        $files = [
            'Listeners/ReplaceCompaniesLocationCode.php',
            'Listeners/ReplaceModalsLocationCode.php',
            'Resources/assets/js/custom-fields-fields.js',
            'Traits/Permissions.php'
        ];

        $directories = [
            'Database/Seeds',
            'Events',
        ];

        foreach ($files as $file) {
            File::delete(base_path('modules/CustomFields/' . $file));
        }

        foreach ($directories as $directory) {
            File::deleteDirectory(base_path('modules/CustomFields/' . $directory));
        }
    }
}
