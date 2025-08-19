<?php

namespace Modules\Estimates\Listeners\Menu;

use Akaunting\Menu\MenuBuilder;
use App\Events\Menu\AdminCreated as Event;
use App\Traits\Modules;
use App\Traits\Permissions;

class Admin
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

        $title = setting('estimates.estimate.name', trans_choice('estimates::general.estimates', 2));

        if (false === $this->canAccessMenuItem($title, 'read-estimates-estimates')) {
            return;
        }

        $salesMenu = $menu->findBy('title', trim(trans_choice('general.sales', 2)));

        $salesMenu->child(
            [
                'route'   => ['estimates.estimates.index', []],
                'title' => $title,
                'order' => 5,
            ]
        );
    }
}
