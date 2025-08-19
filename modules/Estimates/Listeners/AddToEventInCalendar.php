<?php

namespace Modules\Estimates\Listeners;

use App\Models\Document\Document;
use App\Traits\Modules;
use Date;
use Modules\Estimates\Models\EstimateExtraParameter;
use Modules\Calendar\Events\CalendarEventCreated as Event;

class AddToEventInCalendar
{
    use Modules;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        if (! $this->moduleIsEnabled('estimates') || ! $this->moduleIsEnabled('calendar')) {
            return;
        }

        if (! user()->canAny('read-estimates-estimates')) {
            return;
        }

        $estimates = Document::type('estimate')->get();

        foreach ($estimates as $item) {
            if ($item->status == 'invoiced') {
                continue;
            }

            $date = $item->issued_at;

            switch ($item->status) {
                case 'approved':
                    $color = '#6da252';
                    break;

                case 'refused':
                    $color = '#ef3232';
                    break;
                
                default:
                    $color = '#6CC9FF';
                    $date = EstimateExtraParameter::where('document_id', $item->id)->get(['expire_at'])->toArray()[0]['expire_at'];
                    break;
            }

            $date = Date::parse($date)->format('Y-m-d');

            $event->calendar->events[] = [
                'title' => $item->document_number,
                'start' => $date,
                'type' => 'estimates',
                'id' => $item->id,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'show' => route('estimates.estimates.show', $item->id),
                    'edit' => route('estimates.estimates.edit', $item->id),
                    'description' => $item->description . trans('calendar::general.event_description', ['url' => route('estimates.estimates.show', $item->id), 'name' => $item->document_number]),
                    'date' => $date
                ],
            ];
        }
    }
}
