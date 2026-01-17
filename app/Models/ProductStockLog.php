<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStockLog extends Model
{
    protected $fillable = [
        'product_id',
        'old_quantity',
        'new_quantity',
        'changed_by',
        'change_type', // import, manual, etc
        'note',
    ];
}
