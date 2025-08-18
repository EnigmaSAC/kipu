<?php

namespace Modules\CustomFields\Listeners\Update\V30;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished;
use App\Traits\Updates;
use Illuminate\Support\Facades\DB;

class Version3110 extends Listener
{
    use Updates;

    const ALIAS = 'custom-fields';

    const VERSION = '3.1.10';

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

        $this->deleteFolders(['Services']);

        $this->updateColumn();
    }

    public function updateColumn()
    {
        DB::transaction(function () {
            $fields = DB::table('custom_fields_fields')->where('rule', 'like', '%password%')->cursor();

            foreach ($fields as $field) {
                $rule = str_replace('password', 'current_password', $field->rule);

                DB::table('custom_fields_fields')->where('id', $field->id)->update(['rule' => $rule]);
            }
        });
    }
}
