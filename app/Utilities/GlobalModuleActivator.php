<?php

namespace App\Utilities;

use Akaunting\Module\Contracts\ActivatorInterface;
use Akaunting\Module\Module;
use Illuminate\Cache\CacheManager as Cache;
use Illuminate\Config\Repository as Config;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\DB;

class GlobalModuleActivator implements ActivatorInterface
{
    protected Cache ;
    protected Config ;
    protected array  = [];
    protected int  = 0; // ignorado a propósito

    public function __construct(Container )
    {
        ->cache  = ["cache"];
        ->config = ["config"];
        ->load();
    }

    public function is(Module , bool ): bool
    {
        // En consola, permitir para que artisan no se bloquee
        if (app()->runningInConsole()) {
            return true;
        }

         = ->getAlias();
        return (->statuses[] ?? false) === ;
    }

    public function enable(Module ): void
    {
        ->setActive(, true);
    }

    public function disable(Module ): void
    {
        ->setActive(, false);
    }

    public function setActive(Module , bool ): void
    {
         = ->getAlias();

        // Tabla "modules": columna "enabled" global (company_id NULL o cualquiera)
        try {
            DB::table("modules")
                ->where("alias", )
                ->update(["enabled" =>  ? 1 : 0]);
        } catch (\Throwable ) {
            // si aún no existe la tabla (instalador), ignorar
        }

        ->statuses[] = ;
        ->flushCache();
    }

    public function delete(Module ): void
    {
        // No borramos nada; sólo los marcamos como deshabilitados globalmente
        ->setActive(, false);
    }

    public function has(Module ): bool
    {
        return array_key_exists(->getAlias(), ->getStatuses());
    }

    public function setCompany(int ): void
    {
        // Ignorado: comportamiento global
        ->company_id = ;
    }

    public function getStatuses(bool  = false): array
    {
        if ( || empty(->statuses)) {
            ->statuses = ->load();
        }
        return ->statuses;
    }

    protected function load(): array
    {
         = ->config->get("module.cache.key", "akaunting.module") . ".statuses";
         = ->config->get("module.cache.lifetime", 60);

        return ->cache->remember(, , function () {
            return ->readDatabase();
        });
    }

    protected function flushCache(): void
    {
         = ->config->get("module.cache.key", "akaunting.module") . ".statuses";
        ->cache->forget();
    }

    protected function readDatabase(): array
    {
        try {
            if (!DB::getSchemaBuilder()->hasTable("modules")) {
                return [];
            }

             = DB::table("modules")->select("alias", "enabled")->get();
             = [];
            foreach ( as ) {
                [->alias] = (bool) ->enabled;
            }
            return ;
        } catch (\Throwable ) {
            return [];
        }
    }
}
