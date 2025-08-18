<?php

namespace Modules\CustomFields\Listeners;

use App\Events\Module\Installed as Event;
use App\Traits\Permissions;

class FinishInstallation
{
    use Permissions;

    /**
     * Handle the event.
     *
     * @param  Event $event
     *
     * @return void
     */
    public function handle(Event $event)
    {
        if ($event->alias != 'custom-fields') {
            return;
        }

        $this->attachPermissionsToAdminRoles([
            $event->alias . '-fields' => 'c,r,u,d',
            $event->alias . '-settings' => 'r,u',
        ]);
    }
}
