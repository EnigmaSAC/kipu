<?php

namespace Modules\Estimates\Listeners\Setting;

use Akaunting\Menu\MenuBuilder;
use App\Events\Menu\SettingsCreated as Event;
use App\Traits\Modules;
use App\Traits\Permissions;

class ShowInSettingsMenu
{
    use Modules;
    use Permissions;

    /**
     * Handle the event.
     *
     * @param Event $event
     *
     * @return void
     */
    public function handle(Event $event)
    {
        if (false === $this->moduleIsEnabled('estimates')) {
            return;
        }

        /** @var MenuBuilder $menu */
        $menu = $event->menu;

        $title = setting('estimates.estimate.title', trans_choice('estimates::general.estimates', 1));

        if ($this->canAccessMenuItem($title, 'read-estimates-settings-estimate')) {
            $menu->route(
                'estimates.settings.estimate.edit',
                $title,
                [],
                25,
                ['icon' => 'check', 'search_keywords' => trans('estimates::settings.estimate.search_keywords')]
            );
        }
    }
}
