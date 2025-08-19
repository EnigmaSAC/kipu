<?php

use Modules\Estimates\Models\Estimate;
use Modules\Estimates\Notifications\Estimate as Notification;

return [
    'document' => [
        Estimate::TYPE => [
            'alias'               => 'estimates',
            'route'               => [
                'prefix'    => 'estimates',
                'parameter' => 'estimate',
            ],
            'permission'          => [
                'prefix' => 'estimates',
            ],
            'translation'         => [
                'prefix' => 'estimates',
            ],
            'setting'             => [
                'prefix' => 'estimate',
            ],
            'category_type'       => 'income',
            'transaction_type'    => 'income',
            'contact_type'        => 'customer',
            'search_string_model' => Estimate::class,
            'image_empty_page'    => 'public/img/empty_pages/default.png',
            'notification'        => [
                'class'          => Notification::class,
                'notify_contact' => true,
                'notify_user'    => true,
            ],
            'script'              => [
                'folder' => 'common',
                'file'   => 'documents',
            ],
        ],
    ],
];
