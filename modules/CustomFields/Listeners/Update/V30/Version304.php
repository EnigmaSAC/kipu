<?php

namespace Modules\CustomFields\Listeners\Update\V30;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class Version304 extends Listener
{
    const ALIAS = 'custom-fields';

    const VERSION = '3.0.4';

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

        $this->updateDefaultValues();

        $this->updateClass();
    }

    public function updateDatabase()
    {
        Artisan::call('module:migrate', ['alias' => self::ALIAS, '--force' => true, '--subpath' => '2022_07_21_000000_custom_fields_v304.php']);
    }

    public function updateDefaultValues()
    {
        foreach (DB::table('custom_fields_fields')->cursor() as $field) {
            $type = DB::table('custom_fields_types')->where('id', $field->type_id)->first()->type ?? null;

            if (in_array($type, ['select', 'checkbox'])) {
                $field->default = DB::table('custom_fields_field_type_options')->where('field_id', $field->id)->first()->value ?? null;
                $field->save();
            }
        }
    }

    public function updateClass()
    {
        DB::table('custom_fields_fields')->update(['class' => 'sm:col-span-3']);
    }
}
