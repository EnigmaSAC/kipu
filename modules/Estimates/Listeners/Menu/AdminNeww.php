<?php

namespace Modules\Estimates\Listeners\Menu;

use Akaunting\Menu\MenuBuilder;
use App\Events\Menu\NewwCreated as Event;
use App\Traits\Modules;
use App\Traits\Permissions;

class AdminNeww
{
    use Modules;
    use Permissions;

    public function handle(Event $event): void
    {
        if (false === $this->moduleIsEnabled('estimates')) {
            return;
        }

        /** @var MenuBuilder $menu */
        $menu = $event->menu;

        $title = setting('estimates.estimate.title', trans_choice('estimates::general.estimates', 1));

        if ($this->canAccessMenuItem($title, 'create-estimates-estimates')) {
            $menu->route('estimates.estimates.create', $title, [], 5, ['icon' => 'check']);
        }
    }
}
