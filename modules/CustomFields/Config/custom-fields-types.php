<?php

return [
    [
        'code' => 'select',
        'name' => 'custom-fields::general.type.select',
        'rules' => ['required'],
    ],
    [
        'code' => 'toggle',
        'name' => 'custom-fields::general.type.toggle',
        'rules' => ['required'],
    ],
    [
        'code' => 'checkbox',
        'name' => 'custom-fields::general.type.checkbox',
        'rules' => ['required'],
    ],
    [
        'code' => 'text',
        'name' => 'custom-fields::general.type.text',
        'rules' => ['required', 'string', 'integer', 'email', 'url', 'password'],
    ],
    [
        'code' => 'textarea',
        'name' => 'custom-fields::general.type.textarea',
        'rules' => ['required', 'string', 'integer', 'email', 'url', 'password'],
    ],
    [
        'code' => 'date',
        'name' => 'custom-fields::general.type.date',
        'rules' => ['required', 'date'],
    ],
    [
        'code' => 'time',
        'name' => 'custom-fields::general.type.time',
        'rules' => ['required'],
    ],
    [
        'code' => 'dateTime',
        'name' => 'custom-fields::general.type.date&time',
        'rules' => ['required', 'date'],
    ],
];
