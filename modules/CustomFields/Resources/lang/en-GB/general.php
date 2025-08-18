<?php

return [

    'name'              => 'Custom Fields',
    'description'       => 'Add unlimited custom fields to different pages',

    'fields'            => 'Field|Fields',
    'locations'         => 'Location|Locations',
    'sort'              => 'Sort',
    'order'             => 'Position',
    'depend'            => 'Depend',
    'show'              => 'Show',
    'design'            => 'Design',
    'add_value'         => 'Add a Value',

    'location_defined'  => 'Location Disabled: :location',

    'form' => [
        'name'          => 'Name',
        'code'          => 'Code',
        'icon'          => 'FontAwesome Icon',
        'class'         => 'CSS Class',
        'required'      => 'Required',
        'rule'          => 'Validation',
        'before'        => 'Before',
        'after'         => 'After',
        'value'         => 'Value',
        'shows'         => [
            'always'    => 'Always',
            'never'     => 'Never',
            'if_filled' => 'If Filled'
        ],
        'items'         => 'Items',
    ],

    'type' => [
        'select'        => 'Select',
        'radio'         => 'Radio',
        'checkbox'      => 'CheckBox',
        'text'          => 'Text',
        'textarea'      => 'TextArea',
        'date'          => 'Date',
        'time'          => 'Time',
        'date&time'     => 'Date & Time',
        'enabled'       => 'Enabled',
        'toggle'        => 'Toggle',
    ],

    'item' => [
        'action'   => 'Item Action',
        'name'     => 'Item Name',
        'quantity' => 'Item Quantity',
        'price'    => 'Item Price',
        'taxes'    => 'Item Taxes',
        'total'    => 'Item Total',
    ],

    'validation_rules' => [
        'required' => 'Required',
        'string' => 'String',
        'integer' => 'Integer',
        'date' => 'Date',
        'email' => 'Email',
        'url' => 'URL',
        'password' => 'Password',
    ],

    'section-head' => [
        'general' => 'This information is visible in the title of the new field you create.',
        'type' => 'Select the field format that you want to see in the interface. The validation allows you to set rules for fields. You can set the Default Value for the Text Field Types.',
        'location' => 'Select the location and the position of the fields on the form page.',
        'design' => 'Set the width of the new fields on the form page and select the visibility of the field on the show page.',
    ],

    'empty' => [
        'fields' => 'Custom fields allows you to create fields such as Text, Select Box, Checkbox, etc. to several areas to let you manage how your books are recorded.',
    ],

];
