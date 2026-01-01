<?php

namespace App\Services;

use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductFlat;
use Webkul\Product\Listeners\ProductFlat as ProductFlatListener;
use Webkul\Core\Models\Channel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class ProductFlatSyncService
{
    /**
     * @var ProductFlatListener
     */
    protected $productFlatListener;

    /**
     * @var Channel
     */
    protected $defaultChannel;

    public function __construct(ProductFlatListener $productFlatListener)
    {
        $this->productFlatListener = $productFlatListener;
        $this->defaultChannel = Channel::where('code', 'default')->first();
    }

    /**
     * Sync all products to product flat
     *
     * @param int|null $batchSize
     * @return array
     */
    public function syncAllProducts($batchSize = 100)
    {
        $totalProducts = Product::count();
        $synced = 0;
        $errors = [];

        Log::info('Starting product flat sync', ['total_products' => $totalProducts]);

        Product::chunk($batchSize, function ($products) use (&$synced, &$errors) {
            foreach ($products as $product) {
                try {
                    $this->syncSingleProduct($product);
                    $synced++;
                } catch (Exception $e) {
                    $errors[] = [
                        'product_id' => $product->id,
                        'sku' => $product->sku,
                        'error' => $e->getMessage()
                    ];
                    Log::error('Failed to sync product to flat', [
                        'product_id' => $product->id,
                        'sku' => $product->sku,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        });

        Log::info('Product flat sync completed', [
            'synced' => $synced,
            'errors' => count($errors)
        ]);

        return [
            'total' => $totalProducts,
            'synced' => $synced,
            'errors' => $errors
        ];
    }

    /**
     * Sync single product to product flat
     *
     * @param Product $product
     * @return bool
     */
    public function syncSingleProduct(Product $product)
    {
        try {
            // Use the existing ProductFlat listener to create flat data
            $this->productFlatListener->afterProductCreatedUpdated($product);
            
            Log::debug('Product synced to flat', [
                'product_id' => $product->id,
                'sku' => $product->sku
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Error syncing product to flat', [
                'product_id' => $product->id,
                'sku' => $product->sku,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Sync products by IDs
     *
     * @param array $productIds
     * @return array
     */
    public function syncProductsByIds(array $productIds)
    {
        $products = Product::whereIn('id', $productIds)->get();
        $synced = 0;
        $errors = [];

        foreach ($products as $product) {
            try {
                $this->syncSingleProduct($product);
                $synced++;
            } catch (Exception $e) {
                $errors[] = [
                    'product_id' => $product->id,
                    'sku' => $product->sku,
                    'error' => $e->getMessage()
                ];
            }
        }

        return [
            'total' => count($productIds),
            'synced' => $synced,
            'errors' => $errors
        ];
    }

    /**
     * Clear all product flat data and resync
     *
     * @param int|null $batchSize
     * @return array
     */
    public function clearAndResync($batchSize = 100)
    {
        Log::info('Clearing product flat table');
        
        DB::beginTransaction();
        
        try {
            // Clear existing flat data
            ProductFlat::truncate();
            
            // Resync all products
            $result = $this->syncAllProducts($batchSize);
            
            DB::commit();
            
            Log::info('Product flat cleared and resynced successfully');
            
            return $result;
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error during clear and resync', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Check if product exists in flat table
     *
     * @param int $productId
     * @param string|null $channel
     * @param string|null $locale
     * @return bool
     */
    public function existsInFlat($productId, $channel = 'default', $locale = 'en')
    {
        return ProductFlat::where('product_id', $productId)
            ->where('channel', $channel)
            ->where('locale', $locale)
            ->exists();
    }

    /**
     * Get sync statistics
     *
     * @return array
     */
    public function getSyncStatistics()
    {
        $totalProducts = Product::count();
        $totalFlat = ProductFlat::distinct('product_id')->count();
        $missingSyncCount = $totalProducts - $totalFlat;

        return [
            'total_products' => $totalProducts,
            'total_flat_records' => ProductFlat::count(),
            'unique_products_in_flat' => $totalFlat,
            'missing_sync_count' => $missingSyncCount,
            'sync_percentage' => $totalProducts > 0 ? round(($totalFlat / $totalProducts) * 100, 2) : 0
        ];
    }

    /**
     * Get products that are not synced to flat
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUnsyncedProducts($limit = 100)
    {
        $syncedProductIds = ProductFlat::distinct('product_id')->pluck('product_id');
        
        return Product::whereNotIn('id', $syncedProductIds)->limit($limit)->get();
    }

    /**
     * Sync only missing products
     *
     * @return array
     */
    public function syncMissingProducts()
    {
        $unsyncedProducts = $this->getUnsyncedProducts(1000);
        $synced = 0;
        $errors = [];

        Log::info('Starting sync for missing products', ['count' => $unsyncedProducts->count()]);

        foreach ($unsyncedProducts as $product) {
            try {
                $this->syncSingleProduct($product);
                $synced++;
            } catch (Exception $e) {
                $errors[] = [
                    'product_id' => $product->id,
                    'sku' => $product->sku,
                    'error' => $e->getMessage()
                ];
            }
        }

        return [
            'total' => $unsyncedProducts->count(),
            'synced' => $synced,
            'errors' => $errors
        ];
    }
}