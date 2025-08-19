<?php

use Illuminate\Support\Facades\Route;

/**
 * 'admin' middleware and 'employees' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::admin('dynamic-document-notes', function () {
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('/', 'Settings@edit')->name('edit');
        Route::post('/', 'Settings@update')->name('update');
    });

    Route::get('document/account/note', 'Documents@note')->name('account.note');
});
