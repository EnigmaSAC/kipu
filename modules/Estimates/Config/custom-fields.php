<?php

use App\Models\Document\Document;
use Modules\Estimates\Models\Estimate;

return [
    Document::class => [
        [
            'type'        => Estimate::TYPE,
            'location'    => [
                'code' => 'sales-estimates',
                'name' => 'estimates::general.estimates',
            ],
            'sort_orders' => [
                'title'              => 'settings.invoice.title',
                'subheading'         => 'settings.invoice.subheading',
                'company logo'       => 'settings.company.logo',
                //-------------------------
                'issued_at'          => 'estimates::estimates.invoice_date',
                'document_number'    => 'estimates::estimates.invoice_number',
                'expiry_date'        => 'estimates::general.expiry_date',
                //-------------------------
                'item_custom_fields' => ['general.items', 2],
                'notes'              => ['general.notes', 2],
                //--------------------------
                'footer'             => 'general.footer',
                'category_id'        => ['general.categories', 1],
                'attachment'         => 'general.attachment',
            ],
            'views'       => [
                'crud'       => [
                    'estimates::estimates.create',
                    'estimates::estimates.edit',
                    'estimates::portal.estimates.edit',
                ],
                'show'       => [
                    'estimates::estimates.print_classic',
                    'estimates::estimates.print_default',
                    'estimates::estimates.print_modern',
                ],
                'show_types' => [
                    'print' => [
                        'estimates::estimates.print_classic',
                        'estimates::estimates.print_default',
                        'estimates::estimates.print_modern',
                    ],
                ],
            ],
        ],
    ],
];
