<?php

namespace App\Utilities;

use Akaunting\Module\Contracts\ActivatorInterface;
use Akaunting\Module\Module;

class ModuleActivator implements ActivatorInterface
{
    /**
     * Estados simples en memoria (por alias).
     * No persiste: suficiente para satisfacer la interfaz y permitir el arranque.
     */
    protected array $statuses = [];

    public function __construct($app)
    {
        // No necesitamos nada del contenedor para esta versión mínima.
        $this->statuses = [];
    }

    /** ¿El módulo tiene el estado $active? */
    public function is(Module $module, bool $active): bool
    {
        return ($this->statuses[$module->getAlias()] ?? false) === $active;
    }

    /** Habilitar módulo */
    public function enable(Module $module): void
    {
        $this->setActive($module, true);
    }

    /** Deshabilitar módulo */
    public function disable(Module $module): void
    {
        $this->setActive($module, false);
    }

    /** Verificar estado explícito */
    public function hasStatus(Module $module, bool $active): bool
    {
        return ($this->statuses[$module->getAlias()] ?? false) === $active;
    }

    /** Fijar estado */
    public function setActive(Module $module, bool $active): void
    {
        $this->statuses[$module->getAlias()] = $active;
    }

    /** Eliminar tracking del módulo */
    public function delete(Module $module): void
    {
        unset($this->statuses[$module->getAlias()]);
    }

    /** Obtener todos los estados */
    public function getStatuses(): array
    {
        return $this->statuses;
    }

    /** Requerido por la interfaz: limpiar estados */
    public function reset(): void
    {
        $this->statuses = [];
    }
}
