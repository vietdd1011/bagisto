<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('stock-documents/create', [
        \Webkul\Admin\Http\Controllers\StockDocumentController::class,
        'create',
    ])->name('stock_documents.create');
    Route::post('stock-documents', [
        \Webkul\Admin\Http\Controllers\StockDocumentController::class,
        'store',
    ])->name('stock_documents.store');
});
