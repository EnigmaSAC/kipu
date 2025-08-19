<?php

namespace Modules\Estimates\Listeners\Update\V21;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Version219 extends Listener
{
    const ALIAS = 'estimates';

    const VERSION = '2.1.9';

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

        $this->deleteCustomFieldLocation();
    }

    protected function deleteCustomFieldLocation()
    {
        if (false === Schema::hasTable('custom_fields_locations')) {
            return;
        }

        DB::table('custom_fields_locations')
          ->where('code', 'estimates::estimates')
          ->where('name', '<>', 'Estimates')
          ->whereNull('deleted_at')
          ->take(1)
          ->delete();
    }
}
