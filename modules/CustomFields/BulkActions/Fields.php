<?php

namespace Modules\CustomFields\BulkActions;

use App\Abstracts\BulkAction;
use Modules\CustomFields\Models\Field;

class Fields extends BulkAction
{
    public $model = Field::class;

    public $text = 'custom-fields::general.fields';

    public $path = [
        'group' => 'custom-fields',
        'type' => 'fields',
    ];

    public $actions = [
        'enable' => [
            'icon' => 'check_circle',
            'name' => 'general.enable',
            'message' => 'bulk_actions.message.enable',
            'permission' => 'update-custom-fields-settings',
        ],
        'disable' => [
            'icon' => 'hide_source',
            'name' => 'general.disable',
            'message' => 'bulk_actions.message.disable',
            'permission' => 'update-custom-fields-settings',
        ],
        'delete' => [
            'icon' => 'delete',
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'permission' => 'update-custom-fields-settings',
        ],
    ];
}
