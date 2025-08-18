<?php

namespace Modules\CustomFields\Listeners\Update\V30;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished;
use Illuminate\Support\Facades\DB;

class Version311 extends Listener
{
    const ALIAS = 'custom-fields';

    const VERSION = '3.1.1';

    /**
     * Handle the event.
     *
     * @param  $event
     *
     * @return void
     */
    public function handle(UpdateFinished $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->updateColumn();
    }

    public function updateColumn()
    {
        $width_options = [
            'sm:col-span-1' => '16',
            'sm:col-span-2' => '33',
            'sm:col-span-3' => '50',
            'sm:col-span-6' => '100',
        ];

        foreach ($width_options as $class => $width) {
            DB::table('custom_fields_fields')
                ->where('width', $class)
                ->update(['width' => $width]);
        }

        $locations = [
            'sales-invoices' => 'category_id',
            'purchases-bills' => 'category_id',
            'transactions-incomes' => 'reference',
            'transcations-expenses' => 'reference',
            'banking-transfers' => 'reference',
        ];

        foreach ($locations as $location => $new_location) {
            DB::table('custom_fields_fields')
                ->where('location', $location)
                ->where('sort', 'attachment_input_start')
                ->update(['sort' => $new_location . '_input_start']);

            DB::table('custom_fields_fields')
                ->where('location', $location)
                ->where('sort', 'attachment_input_end')
                ->update(['sort' => $new_location . '_input_end']);
        }
    }
}
