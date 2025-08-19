<?php

return [

    /*
    'fields' => [

        'type'              => ['general.types', 1],
        'name'              => 'general.name',
        'number'            => 'accounts.number',
        'currency_code'     => ['general.currencies', 1],
        'opening_balance'   => 'accounts.opening_balance',
        'default_account'   => 'accounts.default_account',
        'bank_name'         => 'accounts.bank_name',
        'bank_phone'        => 'accounts.bank_phone',
        'bank_address'      => 'accounts.bank_address',

    ],
    */

    'fields' => [
        '{type}',
        '{name}',
        '{number}',
        '{currency_code}',
        '{opening_balance}',
        '{bank_name}',
        '{bank_phone}',
        '{bank_address}',
    ],

    'default' => 'Bank Name: {bank_name}<br>Account Currency: {currency_code}<br>Account Number: {number}',

];
