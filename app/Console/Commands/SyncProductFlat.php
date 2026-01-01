<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ProductFlatSyncService;
use Exception;

class SyncProductFlat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:sync-flat 
                           {--clear : Clear existing flat data before sync}
                           {--missing : Sync only missing products}
                           {--batch=100 : Batch size for processing}
                           {--stats : Show sync statistics only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync products to product flat table';

    /**
     * @var ProductFlatSyncService
     */
    protected $syncService;

    /**
     * Create a new command instance.
     *
     * @param ProductFlatSyncService $syncService
     * @return void
     */
    public function __construct(ProductFlatSyncService $syncService)
    {
        parent::__construct();
        $this->syncService = $syncService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            // Show statistics only
            if ($this->option('stats')) {
                $this->showStatistics();
                return 0;
            }

            // Clear and resync all
            if ($this->option('clear')) {
                $this->info('Clearing existing flat data and resyncing all products...');
                $result = $this->syncService->clearAndResync($this->option('batch'));
                $this->displayResults($result);
                return 0;
            }

            // Sync only missing products
            if ($this->option('missing')) {
                $this->info('Syncing missing products only...');
                $result = $this->syncService->syncMissingProducts();
                $this->displayResults($result);
                return 0;
            }

            // Default: sync all products
            $this->info('Starting product flat sync...');
            $result = $this->syncService->syncAllProducts($this->option('batch'));
            $this->displayResults($result);
            
            return 0;
        } catch (Exception $e) {
            $this->error('Error during sync: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Display sync results
     *
     * @param array $result
     * @return void
     */
    private function displayResults(array $result)
    {
        $this->info("Sync completed!");
        $this->table(['Metric', 'Value'], [
            ['Total Products', $result['total']],
            ['Successfully Synced', $result['synced']],
            ['Errors', count($result['errors'])],
            ['Success Rate', $result['total'] > 0 ? round(($result['synced'] / $result['total']) * 100, 2) . '%' : '0%']
        ]);

        if (!empty($result['errors'])) {
            $this->warn('Errors occurred during sync:');
            $errorData = [];
            foreach ($result['errors'] as $error) {
                $errorData[] = [
                    $error['product_id'], 
                    $error['sku'], 
                    substr($error['error'], 0, 50) . '...'
                ];
            }
            $this->table(['Product ID', 'SKU', 'Error'], $errorData);
        }
    }

    /**
     * Show sync statistics
     *
     * @return void
     */
    private function showStatistics()
    {
        $stats = $this->syncService->getSyncStatistics();
        
        $this->info('Product Flat Sync Statistics:');
        $this->table(['Metric', 'Value'], [
            ['Total Products', $stats['total_products']],
            ['Total Flat Records', $stats['total_flat_records']],
            ['Unique Products in Flat', $stats['unique_products_in_flat']],
            ['Missing Products', $stats['missing_sync_count']],
            ['Sync Percentage', $stats['sync_percentage'] . '%']
        ]);

        if ($stats['missing_sync_count'] > 0) {
            $this->warn("There are {$stats['missing_sync_count']} products not synced to flat table.");
            $this->info("Run 'php artisan product:sync-flat --missing' to sync only missing products.");
        } else {
            $this->info('All products are synced to flat table!');
        }
    }
}