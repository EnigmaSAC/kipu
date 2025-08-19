<?php
return [
    'menu' => [
        'estimate_new_customer' => [
            'title'       => 'New Estimate',
            'description' => '<strong>:estimate_number</strong> estimate is created. ' .
                             'You can <a href=":estimate_portal_link">click here</a> to see the details.',
        ],
        'estimate_remind_customer' => [
            'title'       => 'Estimate Reminder',
            'description' => '<strong>:estimate_number</strong> estimate will be expired at <strong>:estimate_expiry_date</strong>. ' .
                             'You can <a href=":estimate_portal_link">click here</a> to see the details.',
        ],
        'estimate_remind_admin' => [
            'title'       => 'Estimate Reminder',
            'description' => '<strong>:estimate_number</strong> estimate will be expired at <strong>:estimate_expiry_date</strong>. ' .
                             'You can <a href=":estimate_admin_link">click here</a> to see the details.',
        ],
        'estimate_view_admin' => [
            'title'       => 'Estimate Viewed',
            'description' => '<strong>:customer_name</strong> has viewed the <strong>:estimate_number</strong> estimate. ' .
                             'You can <a href=":estimate_admin_link">click here</a> to see the details.',
        ],
        'estimate_approved_admin' => [
            'title'       => 'Estimate Approved',
            'description' => ':customer_name approved <strong>:estimate_number</strong> estimate. ' .
                             'You can <a href=":estimate_admin_link">click here</a> to see the details.',
        ],
        'estimate_refused_admin' => [
            'title'       => 'Estimate Refused',
            'description' => ':customer_name refused <strong>:estimate_number</strong> estimate. ' .
                             'You can <a href=":estimate_admin_link">click here</a> to see the details.',
        ],
    ],
];
