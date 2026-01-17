<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockDocumentProduct extends Model
{
    protected $fillable = [
        'stock_document_id',
        'product_id',
        'product_number',
        'sku',
        'color',
        'size',
        'attribute_family_id',
        'type',
        'price',
        'quantity',
    ];

    public function stockDocument()
    {
        return $this->belongsTo(StockDocument::class);
    }

    public function product()
    {
        return $this->belongsTo(\Webkul\Product\Models\Product::class);
    }
}
