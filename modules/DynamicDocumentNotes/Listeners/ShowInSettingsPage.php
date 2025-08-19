<?php

namespace Modules\DynamicDocumentNotes\Listeners;

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
        $title = trim(trans('dynamic-document-notes::general.name'));

        if ($this->moduleIsEnabled('dynamic-document-notes') && $this->canAccessMenuItem($title, 'read-dynamic-document-notes-settings')) {
            $event->menu->route('dynamic-document-notes.settings.edit', $title, [], 1020,
            [
                'icon' => 'dynamic_form',
                'search_keywords' => trans('dynamic-document-notes::general.description')
            ]);
        }
    }
}