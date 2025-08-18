<?php

return [
    'validation' => [
        'required'  => 'custom-fields::general.validation_rules.required',
        'string'    => 'custom-fields::general.validation_rules.string',
        'integer'   => 'custom-fields::general.validation_rules.integer',
        'date'      => 'custom-fields::general.validation_rules.date',
        'email'     => 'custom-fields::general.validation_rules.email',
        'url'       => 'custom-fields::general.validation_rules.url',
        'password'  => 'custom-fields::general.validation_rules.password',
    ],

    'cant_be' => [
        'required'  => null,
        'string'    => 'integer',
        'integer'   => ['string', 'date', 'email', 'url'],
        'date'      => ['url', 'email', 'password'],
        'email'     => ['password', 'url', 'date', 'integer'],
        'url'       => ['email', 'password', 'date', 'integer'],
        'password'  => ['email', 'url', 'date'],
    ]
];