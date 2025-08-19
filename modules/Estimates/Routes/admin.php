<?php

use Illuminate\Support\Facades\Route;

/**
 * 'admin' middleware and 'estimates' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::admin(
    'estimates',
    function () {
        Route::group(
            [
                'as'        => 'settings.estimate.',
                'prefix'    => 'settings/estimate',
                'namespace' => 'Settings',
            ],
            function () {
                Route::get('/', 'Estimate@edit')->name('edit');
                Route::patch('/', 'Estimate@update')->name('update');
            }
        );

        Route::group(
            [
                'as'        => 'modals.estimates.',
                'prefix'    => 'modals/{estimate}',
                'namespace' => 'Modals',
            ],
            function () {
                Route::resource('emails', 'EstimateEmails', ['names' => 'emails']);
                Route::resource('share', 'EstimateShare', ['names' => 'share']);
            }
        );
    }
);

Route::admin(
    'estimates',
    function () {
        Route::group(
            ['prefix' => 'estimates'],
            function () {
                Route::group(
                    ['as' => 'estimates.'],
                    function () {
                        Route::get('{estimate}/send', 'Estimates@markSent')->name('sent');
                        Route::get('{estimate}/approve', 'Estimates@markApproved')->name('approve');
                        Route::get('{estimate}/refuse', 'Estimates@markRefused')->name('refuse');
                        Route::get('{estimate}/email', 'Estimates@emailEstimate')->name('email');
                        Route::get('{estimate}/convert', 'Estimates@convertToInvoice')->name('convert');
                        Route::get('{estimate}/convert-to-sales-order', 'Estimates@convertToSalesOrder')->name(
                            'convert-to-sales-order'
                        );
                        Route::get('{estimate}/print', 'Estimates@printEstimate')->name('print');
                        Route::get('{estimate}/pdf', 'Estimates@pdfEstimate')->name('pdf');
                        Route::get('{estimate}/duplicate', 'Estimates@duplicate')->name('duplicate');

                        Route::post('import', 'Estimates@import')->name('import');
                        Route::get('export', 'Estimates@export')->name('export');
                    }
                );

                Route::get('addItem', 'Estimates@addItem')->middleware(['money'])->name('estimate.add.item');
            }
        );

        Route::resource('estimates', 'Estimates', ['middleware' => ['date.format', 'money', 'dropzone']]);
    },
    ['prefix' => 'sales']
);
