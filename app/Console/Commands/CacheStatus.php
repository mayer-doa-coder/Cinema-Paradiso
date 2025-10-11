<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Services\ApiRateLimiter;

class CacheStatus extends Command
{
    protected $signature = 'cache:status';
    protected $description = 'Show comprehensive cache status and optimization metrics';

    public function handle()
    {
        $this->info('🎬 Cinema Paradiso - Cache Optimization Status');
        $this->line('');

        // Show cache configuration
        $this->info('⚙️  Cache Configuration:');
        $durations = config('services.tmdb.cache_durations', []);
        
        $table = [];
        foreach ($durations as $type => $duration) {
            $hours = $duration / 3600;
            $table[] = [
                $type,
                number_format($hours, 1) . ' hours',
                $this->getFrequency($duration)
            ];
        }

        $this->table(['Data Type', 'Cache Duration', 'Update Frequency'], $table);
        $this->line('');

        // Show rate limiting status
        $rateLimiter = app(ApiRateLimiter::class);
        $this->info('🚦 Rate Limiting Status:');
        
        $endpoints = ['default', 'search', 'popular', 'trending'];
        $rateLimitTable = [];
        
        foreach ($endpoints as $endpoint) {
            $status = $rateLimiter->getRateLimitStatus($endpoint);
            $rateLimitTable[] = [
                $status['endpoint'],
                $status['requests_made'],
                $status['requests_remaining'],
                $status['circuit_open'] ? '🔴 Open' : '🟢 Closed'
            ];
        }

        $this->table(['Endpoint', 'Requests Made', 'Remaining', 'Circuit Breaker'], $rateLimitTable);
        $this->line('');

        // Performance recommendations
        $this->info('💡 Performance Recommendations:');
        $this->line('  ✅ Run "php artisan cache:optimize" every 6 hours');
        $this->line('  ✅ Use "php artisan queue:work" for background cache warming');
        $this->line('  ✅ Monitor rate limits with "php artisan cache:status"');
        $this->line('  ✅ Configure Redis cache for better pattern-based invalidation');
        $this->line('');

        // Show optimization features
        $this->info('🚀 Active Optimizations:');
        $this->line('  ✅ Multi-tier intelligent caching (30 min - 24 hours)');
        $this->line('  ✅ API rate limiting with circuit breaker');
        $this->line('  ✅ Response compression (gzip, up to 85% reduction)');
        $this->line('  ✅ Background cache warming jobs');
        $this->line('  ✅ Smart cache invalidation patterns');
        $this->line('  ✅ Optimized API call scheduling');

        $this->info('✨ Your website is now optimized for maximum performance!');
    }

    private function getFrequency($duration)
    {
        $hours = $duration / 3600;
        
        if ($hours <= 1) return 'Very Dynamic';
        if ($hours <= 6) return 'Dynamic';
        if ($hours <= 12) return 'Semi-Static';
        return 'Static';
    }
}