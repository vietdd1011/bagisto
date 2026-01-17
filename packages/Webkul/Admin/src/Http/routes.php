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

    // API routes for autocomplete
    Route::get('stock-documents/search-products', [
        \Webkul\Admin\Http\Controllers\StockDocumentController::class,
        'searchProducts',
    ])->name('stock_documents.search_products');

    Route::get('stock-documents/get-product-by-sku', [
        \Webkul\Admin\Http\Controllers\StockDocumentController::class,
        'getProductBySku',
    ])->name('stock_documents.get_product_by_sku');

    Route::get('stock-documents/get-colors', [
        \Webkul\Admin\Http\Controllers\StockDocumentController::class,
        'getColors',
    ])->name('stock_documents.get_colors');

    Route::get('stock-documents/get-sizes', [
        \Webkul\Admin\Http\Controllers\StockDocumentController::class,
        'getSizes',
    ])->name('stock_documents.get_sizes');
});

