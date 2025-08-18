<?php

namespace Modules\CustomFields\Listeners\Export;

use App\Events\Export\HeadingsPreparing;
use Modules\CustomFields\Traits\CustomFields;

class AppendHeadings
{
    use CustomFields;

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(HeadingsPreparing $event)
    {
        $fields = $this->getExportableFields(get_class($event->class));

        if ($fields->isEmpty()) {
            return;
        }

        $event->class->fields = array_merge($event->class->fields, $fields->pluck('code')->toArray());
    }
}
