<?php

return [

    // Usa nuestro activador
    'activator' => App\Utilities\ModuleActivator::class,

    // Ruta de módulos
    'paths' => [
        'modules' => base_path('modules'),
    ],

    // Cache básica para estados de módulos
    'cache' => [
        'enabled'  => true,
        'key'      => 'module',
        'lifetime' => 60,
    ],
];
