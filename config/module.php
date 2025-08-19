<?php
return [
    "cache" => [
        "enabled"  => true,
        "key"      => "module",
        "lifetime" => null,
    ],

    "paths" => [
        "modules" => base_path("modules"),
        "generator" => [
            "config"    => "Config",
            "migration" => "Database/Migrations",
            "seeder"    => "Database/Seeders",
            "factory"   => "Database/Factories",
            "command"   => "Console",
            "route"     => "Routes",
            "provider"  => "Providers",
            "assets"    => "Resources/assets",
            "lang"      => "Resources/lang",
            "views"     => "Resources/views",
        ],
    ],
];
