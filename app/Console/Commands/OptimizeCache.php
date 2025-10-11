<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CacheOptimizationService;
use App\Services\MovieService;
use App\Services\NewsService;

class OptimizeCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:optimize {--force : Force cache refresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize cache by warming popular content and reducing API calls';

    protected $cacheService;
    protected $movieService;
    protected $newsService;

    public function __construct(
        CacheOptimizationService $cacheService,
        MovieService $movieService,
        NewsService $newsService
    ) {
        parent::__construct();
        $this->cacheService = $cacheService;
        $this->movieService = $movieService;
        $this->newsService = $newsService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting cache optimization...');
        
        if ($this->option('force')) {
            $this->info('🔥 Force mode: Clearing existing cache...');
            \Illuminate\Support\Facades\Cache::flush();
        }

        // Warm popular content
        $this->info('📺 Warming movie content...');
        $results = $this->cacheService->warmPopularContent();
        
        if ($results) {
            foreach ($results as $type => $count) {
                $this->line("  ✅ {$type}: {$count} items cached");
            }
        }

        // Warm homepage data specifically
        $this->info('🏠 Preloading homepage data...');
        $homepageData = $this->cacheService->preloadHomepageData();
        $this->line("  ✅ Homepage data cached");

        // Warm genres (long-term cache)
        $this->info('🎭 Caching genres...');
        $this->movieService->getGenres();
        $this->line("  ✅ Genres cached for 24 hours");

        // Warm celebrity data
        $this->info('⭐ Warming celebrity data...');
        $celebrityIds = [
            1, 2, 3, 500, 1461, 6193, 1892, 2231, 6968, 10297,
            1245, 18918, 51329, 17051, 5293, 8784, 934, 1136, 13240, 62,
            85, 287, 974, 6384, 3084, 16483, 4724, 3292, 103, 514
        ];

        $progress = $this->output->createProgressBar(count($celebrityIds));
        $progress->start();

        foreach (array_chunk($celebrityIds, 5) as $chunk) {
            foreach ($chunk as $id) {
                $this->movieService->getPersonDetails($id);
                $progress->advance();
            }
            // Small delay to avoid rate limiting
            usleep(50000); // 0.05 second
        }

        $progress->finish();
        $this->line('');
        $this->line("  ✅ " . count($celebrityIds) . " celebrities cached");

        // Show cache statistics
        $this->info('📊 Cache Statistics:');
        $this->showCacheStats();

        $this->info('✨ Cache optimization completed successfully!');
        $this->info('💡 Run this command every 6 hours for optimal performance');
        
        return Command::SUCCESS;
    }

    private function showCacheStats()
    {
        $durations = config('services.tmdb.cache_durations');
        
        $this->table(
            ['Data Type', 'Cache Duration', 'Frequency'],
            [
                ['Popular Movies', $this->formatDuration($durations['popular']), 'Updates slowly'],
                ['Top Rated', $this->formatDuration($durations['top_rated']), 'Rarely changes'],
                ['Trending', $this->formatDuration($durations['trending']), 'Updates hourly'],
                ['Genres', $this->formatDuration($durations['genres']), 'Static data'],
                ['Celebrity Data', $this->formatDuration($durations['person']), 'Semi-static'],
                ['Search Results', $this->formatDuration($durations['search']), 'Dynamic'],
            ]
        );
    }

    private function formatDuration($seconds)
    {
        $hours = $seconds / 3600;
        return $hours >= 1 ? $hours . ' hours' : ($seconds / 60) . ' minutes';
    }
}