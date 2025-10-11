<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Services\CacheOptimizationService;
use App\Jobs\WarmCacheJob;

class InvalidateCache extends Command
{
    protected $signature = 'cache:invalidate {type?} {--force} {--rewarm}';
    protected $description = 'Intelligently invalidate and optionally rewarm cache';

    public function handle(CacheOptimizationService $cacheService)
    {
        $type = $this->argument('type') ?? 'all';
        $force = $this->option('force');
        $rewarm = $this->option('rewarm');

        $this->info("Starting cache invalidation for: {$type}");
        
        if ($force || $this->confirm('Are you sure you want to invalidate cache?')) {
            switch ($type) {
                case 'movies':
                    $this->invalidateMovieCache();
                    break;
                case 'celebrities':
                    $this->invalidateCelebrityCache();
                    break;
                case 'news':
                    $this->invalidateNewsCache();
                    break;
                case 'all':
                    $this->invalidateAllCache();
                    break;
                default:
                    $this->error("Unknown cache type: {$type}");
                    return 1;
            }

            if ($rewarm) {
                $this->info('Dispatching cache warming jobs...');
                WarmCacheJob::dispatch('homepage');
                WarmCacheJob::dispatch('popular');
                WarmCacheJob::dispatch('celebrities');
                $this->info('Cache warming jobs dispatched to queue.');
            }

            $this->info('Cache invalidation completed successfully!');
        } else {
            $this->info('Cache invalidation cancelled.');
        }

        return 0;
    }

    private function invalidateMovieCache()
    {
        $patterns = [
            'movies:popular:*',
            'movies:top_rated:*',
            'movies:trending:*',
            'movies:upcoming:*',
            'movies:now_playing:*',
            'movies:search:*',
            'movies:genres',
            'movies:details:*'
        ];

        foreach ($patterns as $pattern) {
            $this->forgetByPattern($pattern);
        }

        $this->info('Movie cache invalidated.');
    }

    private function invalidateCelebrityCache()
    {
        $patterns = [
            'person:*',
            'celebrities:popular:*'
        ];

        foreach ($patterns as $pattern) {
            $this->forgetByPattern($pattern);
        }

        $this->info('Celebrity cache invalidated.');
    }

    private function invalidateNewsCache()
    {
        $patterns = [
            'news:*',
            'articles:*'
        ];

        foreach ($patterns as $pattern) {
            $this->forgetByPattern($pattern);
        }

        $this->info('News cache invalidated.');
    }

    private function invalidateAllCache()
    {
        if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
            Cache::flush();
            $this->info('All cache flushed (Redis).');
        } else {
            $this->invalidateMovieCache();
            $this->invalidateCelebrityCache();
            $this->invalidateNewsCache();
            $this->info('All cache patterns invalidated.');
        }
    }

    private function forgetByPattern($pattern)
    {
        if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
            $keys = Cache::getRedis()->keys($pattern);
            if (!empty($keys)) {
                Cache::getRedis()->del($keys);
                $this->line("Deleted " . count($keys) . " keys matching: {$pattern}");
            }
        } else {
            // For file cache, we can't easily pattern match, so just inform
            $this->line("Pattern-based deletion not supported for file cache: {$pattern}");
        }
    }
}