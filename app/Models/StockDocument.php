<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockDocument extends Model
{
    protected $fillable = [
        // Thêm các trường cần thiết cho phiếu nhập kho
        'document_number',
        'note',
        'created_by',
    ];

    public function products()
    {
        return $this->hasMany(StockDocumentProduct::class);
    }
}
