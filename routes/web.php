<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

/**
 * 'web' middleware applied to all routes
 *
 * @see \App\Providers\Route::mapWebRoutes
 */

if (! Route::has('livewire.scripts')) {
    Livewire::setScriptRoute(function ($handle) {
        $base = request()->getBasePath();

        return Route::get($base . '/vendor/livewire/livewire/dist/livewire.min.js', $handle);
    });
}
