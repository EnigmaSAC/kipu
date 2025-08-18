<?php

namespace Modules\CustomFields\Listeners;

use App\Events\Menu\SettingsCreated as Event;
use App\Traits\Modules;
use App\Traits\Permissions;

class ShowInSettingsPage
{
    use Modules, Permissions;

    /**
     * Handle the event.
     *
     * @param Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        $title = trim(trans('custom-fields::general.name'));

        if ($this->moduleIsEnabled('custom-fields') && $this->canAccessMenuItem($title, 'read-custom-fields-settings')) {
            $event->menu->route('custom-fields.fields.index', $title, [], 120,
            [
                'icon' => 'dashboard_customize',
                'search_keywords' => trans('custom-fields::general.description')
            ]);
        }
    }
}
