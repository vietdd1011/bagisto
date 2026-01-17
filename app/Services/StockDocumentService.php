<?php

namespace App\Services;

use App\Models\StockDocument;
use App\Models\StockDocumentProduct;
use Webkul\Product\Models\Product;
use Illuminate\Support\Facades\DB;
use Exception;

class StockDocumentService
{
    /**
     * Nhập hàng: tạo phiếu nhập và sản phẩm nhập kho
     *
     * @param array $documentData
     * @param array $productsData
     * @return StockDocument
     * @throws Exception
     */
    public function importStock(array $documentData, array $productsData)
    {
        DB::beginTransaction();
        try {
            $stockDocument = StockDocument::create($documentData);
            // Lấy attribute id cho color và size
            $colorAttribute = \Webkul\Attribute\Models\Attribute::where('code', 'color')->first();
            $sizeAttribute = \Webkul\Attribute\Models\Attribute::where('code', 'size')->first();
            foreach ($productsData as $productData) {
                // Xử lý SKU
                if (empty($productData['sku'])) {
                    $productData['sku'] = str_replace(' ', '-', $productData['product_number']);
                }
                // Lấy option id cho color
                $colorOptionId = null;
                if ($colorAttribute && !empty($productData['color'])) {
                    $colorOption = $colorAttribute->options()->where('admin_name', $productData['color'])->first();
                    if ($colorOption) {
                        $colorOptionId = $colorOption->id;
                    }
                }
                // Lấy option id cho size
                $sizeOptionId = null;
                if ($sizeAttribute && !empty($productData['size'])) {
                    $sizeOption = $sizeAttribute->options()->where('admin_name', $productData['size'])->first();
                    if ($sizeOption) {
                        $sizeOptionId = $sizeOption->id;
                    }
                }
                // Kiểm tra sản phẩm đã tồn tại chưa
                $product = Product::where('sku', $productData['sku'])->first();
                if ($product) {
                    // Nếu sản phẩm đã tồn tại, cộng thêm số lượng
                    $oldQuantity = $product->quantity;
                    $product->quantity += $productData['quantity'];
                    $product->save();
                    // Ghi log thay đổi số lượng
                    \App\Models\ProductStockLog::create([
                        'product_id'   => $product->id,
                        'old_quantity' => $oldQuantity,
                        'new_quantity' => $product->quantity,
                        'changed_by'   => $documentData['created_by'] ?? null,
                        'change_type'  => 'import',
                        'note'         => 'Nhập hàng qua stock document',
                    ]);
                } else {
                    // Nếu sản phẩm chưa tồn tại, tạo mới
                    $product = Product::create([
                        'type' => $productData['type'] ?? 'simple',
                        'attribute_family_id' => $productData['attribute_family_id'] ?? 1,
                        'sku' => $productData['sku'],
                        'name' => $productData['product_number'],
                        'price' => $productData['price'],
                        'quantity' => $productData['quantity'],
                    ]);
                    // Tạo product attribute value cho color và size
                    if ($colorAttribute && $colorOptionId) {
                        \Webkul\Product\Models\ProductAttributeValue::create([
                            'product_id' => $product->id,
                            'attribute_id' => $colorAttribute->id,
                            'channel' => 'default',
                            'locale' => 'en',
                            'integer_value' => $colorOptionId,
                        ]);
                    }
                    if ($sizeAttribute && $sizeOptionId) {
                        \Webkul\Product\Models\ProductAttributeValue::create([
                            'product_id' => $product->id,
                            'attribute_id' => $sizeAttribute->id,
                            'channel' => 'default',
                            'locale' => 'en',
                            'integer_value' => $sizeOptionId,
                        ]);
                    }
                }
                // Lưu vào bảng stock_document_products
                $stockDocument->products()->create([
                    'product_id' => $product->id,
                    'product_number' => $productData['product_number'],
                    'sku' => $productData['sku'],
                    'color' => $productData['color'] ?? null,
                    'size' => $productData['size'] ?? null,
                    'attribute_family_id' => $productData['attribute_family_id'] ?? 1,
                    'type' => $productData['type'] ?? 'simple',
                    'price' => $productData['price'],
                    'quantity' => $productData['quantity'],
                ]);
            }
            DB::commit();
            return $stockDocument;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
