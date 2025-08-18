<?php

namespace Modules\CustomFields\Listeners;

use App\Traits\Modules;
use Modules\CustomFields\Traits\CustomFields;
use Modules\PrintTemplate\Events\ShowingTemplateItems as Event;

class ShowingTemplateItems
{
    use Modules, CustomFields;

    /**
     * Handle the event.
     *
     * @param  Event  $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($this->moduleIsDisabled('custom-fields') || $this->moduleIsDisabled('print-template')) {
            return;
        }

        $custom_field_config = config('custom-fields');

        foreach ($custom_field_config as $values) {
            if (isset($values['export'])) {
                unset($values['export']);
            }
            foreach ($values as $value) {
                if (isset($value['type']) && $event->print_template->type == $value['type']) {
                    $this->addFieldsToItems($value['location']['code'], $event);
                } elseif (isset($value['location']['types']) && isset($value['location']['types'][$event->print_template->type])) {
                    $this->addFieldsToItems($value['location']['code'], $event, $event->print_template->type);
                }
            }
        }
    }

    protected function addFieldsToItems(string $code, Event $event, $extra = null): void
    {
        $fields = $this->getFieldsByLocation($code, $extra);

        foreach ($fields as $field) {
            $event->items['custom-fields::general.name']["custom_fields->FirstWhere('field_id', $field->id)->value"] = $field->name;
        }
    }
}
