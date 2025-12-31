<?php

use Illuminate\Support\Facades\Route;
use Webkul\RMA\Http\Controllers\Shop\RMAController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency'], 'prefix' => 'rma'], function () {
    Route::get('', [RMAController::class, 'index'])->name('shop.rma.index');
});