<?php

use Modules\CustomFields\Models\Field;
use Modules\CustomFields\Models\FieldValue;

return [
    Field::class => [
        'columns' => [
            'name' => [
                'searchable' => true,
            ],
            'enabled' => [
                'boolean' => true,
            ],
        ],
    ],
    FieldValue::class => [
        'columns' => [
            'value' => [
                'searchable' => true,
            ],
        ],
    ],
];
