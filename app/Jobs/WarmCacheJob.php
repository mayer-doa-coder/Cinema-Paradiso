<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\CacheOptimizationService;
use Illuminate\Support\Facades\Log;

class WarmCacheJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $cacheType;
    protected $page;
    protected $params;

    /**
     * Create a new job instance.
     */
    public function __construct($cacheType = 'popular', $page = 1, $params = [])
    {
        $this->cacheType = $cacheType;
        $this->page = $page;
        $this->params = $params;
    }

    /**
     * Execute the job.
     */
    public function handle(CacheOptimizationService $cacheService): void
    {
        try {
            Log::info("Starting cache warming job for {$this->cacheType}, page {$this->page}");
            
            switch ($this->cacheType) {
                case 'popular':
                    $cacheService->warmPopularContent();
                    break;
                case 'homepage':
                    $cacheService->preloadHomepageData();
                    break;
                case 'celebrities':
                    $this->warmCelebrities();
                    break;
                default:
                    Log::warning("Unknown cache type: {$this->cacheType}");
            }
            
            Log::info("Cache warming job completed for {$this->cacheType}");
        } catch (\Exception $e) {
            Log::error("Cache warming job failed: " . $e->getMessage());
            throw $e;
        }
    }

    private function warmCelebrities()
    {
        $movieService = app(\App\Services\MovieService::class);
        
        $celebrityIds = [
            1, 2, 3, 500, 1461, 6193, 1892, 2231, 6968, 10297,
            1245, 18918, 51329, 17051, 5293, 8784, 934, 1136, 13240, 62
        ];

        foreach ($celebrityIds as $id) {
            $movieService->getPersonDetails($id);
            // Small delay to avoid rate limiting
            usleep(100000); // 0.1 second
        }
    }
}