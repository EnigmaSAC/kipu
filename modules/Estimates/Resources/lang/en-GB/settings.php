<?php

return [
    'estimate' => [
        'search_keywords' => 'customize, estimate, quote, number, prefix, digit, next, logo,'.
                             ' name, price, quantity, template, title, subheading, footer,'.
                             ' note, hide, due, colour, approval, terms, column',
        'item'            => 'Items',
        'price'           => 'Price',
        'rate'            => 'Rate',
        'quantity'        => 'Quantity',
        'approval_terms'  => 'Approval Terms',
        'choose_template' => 'Choose estimate template',
        'new_customer'    => 'New Estimate Template (sent to customer)',
        'remind_customer' => 'Estimate Reminder Template (sent to customer)',
        'remind_admin'    => 'Estimate Reminder Template (sent to admin)',
        'view_admin'      => 'Estimate View Template (sent to admin)',
        'approved_admin'  => 'Estimate Approved Template (sent to admin)',
        'refused_admin'   => 'Estimate Refused Template (sent to admin)',


        'form_description' => [
            'general'  => 'Set the defaults for formatting your estimate numbers and payment terms.',
            'template' => 'Select one of the templates below for your estimates.',
            'default'  => 'Selecting defaults for estimates will pre-populate titles, subheadings,'.
                          ' notes, and footers. So you don\'t need to edit estimate each time to '.
                          'look more professional.',
            'column'   => 'Customize how the estimate columns are named. '.
                          'If you like to hide item descriptions and amounts in lines,'.
                          ' you can change it here.',
        ],
    ],

    'prefix'     => 'Number Prefix',
    'digit'      => 'Number Digit',
    'next'       => 'Next Number',
    'title'      => 'Title',
    'subheading' => 'Subheading',
    'notes'      => 'Notes',

    'subject' => 'Subject',
    'body'    => 'Body',
    'tags'    => '<strong>Available Tags:</strong> :tag_list',

    'scheduling' => [
        'send_estimate' => 'Send Estimate Reminder',
        'estimate_days' => 'Send Before Expire Days',
    ],
];
