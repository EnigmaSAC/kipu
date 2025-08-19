<?php

use Illuminate\Support\Facades\Route;

/**
 * 'preview' middleware and 'estimates' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::preview(
    'estimates',
    function () {
        Route::get('{estimate}', 'Estimates@preview')->name('show');
        Route::get('{estimate}/print', 'Estimates@printEstimate')->name('print');
        Route::get('{estimate}/pdf', 'Estimates@pdfEstimate')->name('pdf');

        Route::get('{estimate}/approve', 'Estimates@markApproved')->name('approve');
        Route::get('{estimate}/refuse', 'Estimates@markRefused')->name('refuse');
    },
    [
        'as' => 'preview.estimates.estimates.',
        'namespace' => 'Modules\Estimates\Http\Controllers\Portal',
    ]
);
