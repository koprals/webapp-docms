<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\CRUD.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () {
    // custom admin routes
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');

    // Override account info routes
    Route::get('edit-account-info', 'MyAccountController@getAccountInfoForm')->name('backpack.account.info');
    Route::post('edit-account-info', 'MyAccountController@postAccountInfoForm')->name('backpack.account.info.store');
    Route::post('change-password', 'MyAccountController@postChangePasswordForm')->name('backpack.account.password');

    Route::crud('jenis-dokumen', 'JenisDokumenCrudController');
    Route::crud('jenis-permohonan', 'JenisPermohonanCrudController');
    Route::crud('permohonan', 'PermohonanCrudController');
    Route::crud('klien', 'KlienCrudController');
}); // this should be the absolute last line of this file

/**
 * DO NOT ADD ANYTHING HERE.
 */
