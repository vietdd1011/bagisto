<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Attribute\Models\Attribute;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Services\ProductFlatSyncService;

class ProductTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Start database transaction
        DB::beginTransaction();
        
        try {
            $faker = Faker::create();
            $requiredAttributes = Attribute::where('is_required', 1)->get();
            $createdProductIds = [];
            
            $this->command->info('Starting to seed 100 test products...');
            $this->command->getOutput()->progressStart(100);
            
            for ($i = 1; $i <= 100; $i++) {
                $newProduct = Product::create([
                    'type' => 'simple',
                    'attribute_family_id' => 1,
                    'sku' => $faker->unique()->bothify('SKU###??'),
                ]);
                
                $createdProductIds[] = $newProduct->id;
                
                foreach ($requiredAttributes as $attribute) {
                    if($attribute->type =='boolean'){
                        ProductAttributeValue::create([
                            'product_id' => $newProduct->id,
                            'attribute_id' => $attribute->id,
                            'channel' => 'default',
                            'locale' => 'en',
                            'boolean_value' => 1,
                        ]);
                    }else if($attribute->type =='text' && $attribute->validation == 'decimal'){
                        ProductAttributeValue::create([
                            'product_id' => $newProduct->id,
                            'attribute_id' => $attribute->id,
                            'channel' => 'default',
                            'locale' => 'en',
                            'text_value' => $faker->randomFloat(2, 1, 100),
                        ]);
                    }
                    else{
                        ProductAttributeValue::create([
                            'product_id' => $newProduct->id,
                            'attribute_id' => $attribute->id,
                            'channel' => 'default',
                            'locale' => 'en',
                            'text_value' => $faker->word(),
                        ]);
                    }
                }
                
                $this->command->getOutput()->progressAdvance();
            }
            
            // Commit transaction if everything is successful
            DB::commit();
            $this->command->getOutput()->progressFinish();
            $this->command->info('Successfully seeded 100 test products!');
            
            // Now sync to product flat
            $this->command->info('Syncing products to product flat table...');
            $syncService = new ProductFlatSyncService(app(\Webkul\Product\Listeners\ProductFlat::class));
            $syncResult = $syncService->syncProductsByIds($createdProductIds);
            
            $this->command->info("Product flat sync completed: {$syncResult['synced']}/{$syncResult['total']} products synced");
            if (!empty($syncResult['errors'])) {
                $this->command->warn('Some products failed to sync to flat table. Check logs for details.');
            }
            
        } catch (Exception $e) {
            // Rollback transaction on error
            DB::rollback();
            
            $this->command->error('Error occurred while seeding products: ' . $e->getMessage());
            $this->command->error('All changes have been rolled back.');
            
            // Re-throw exception to indicate seeding failed
            throw $e;
        }
    }
}
