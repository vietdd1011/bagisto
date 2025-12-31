<?php

use Illuminate\Support\Facades\Route;
use Webkul\RMA\Http\Controllers\Admin\RMAController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/rma'], function () {
    Route::controller(RMAController::class)->group(function () {
        Route::get('', 'index')->name('admin.rma.index');
    });
});