<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CacheOptimizationService
{
    private $movieService;
    private $newsService;
    
    public function __construct(MovieService $movieService, NewsService $newsService)
    {
        $this->movieService = $movieService;
        $this->newsService = $newsService;
    }

    /**
     * Get cache duration for specific data type
     */
    private function getCacheDuration($type)
    {
        $durations = config('services.tmdb.cache_durations', []);
        return $durations[$type] ?? config('services.tmdb.cache_duration', 3600);
    }

    /**
     * Batch fetch and cache popular data to minimize API calls
     */
    public function warmPopularContent()
    {
        $results = [];
        
        try {
            // Warm popular movies (first 3 pages) in parallel-like fashion
            $popularMovies = [];
            for ($page = 1; $page <= 3; $page++) {
                $cacheKey = "tmdb_popular_page_{$page}";
                if (!Cache::has($cacheKey)) {
                    $data = $this->movieService->getPopularMovies($page);
                    if ($data) {
                        $popularMovies[] = $data;
                        Cache::put($cacheKey, $data, $this->getCacheDuration('popular'));
                    }
                }
            }
            $results['popular_movies'] = count($popularMovies);

            // Warm trending movies
            $trendingCacheKey = "tmdb_trending_page_1";
            if (!Cache::has($trendingCacheKey)) {
                $trending = $this->movieService->getTrendingMovies(1);
                if ($trending) {
                    Cache::put($trendingCacheKey, $trending, $this->getCacheDuration('trending'));
                    $results['trending_movies'] = 1;
                }
            }

            // Warm genres (long cache)
            $genresCacheKey = "tmdb_genres";
            if (!Cache::has($genresCacheKey)) {
                $genres = $this->movieService->getGenres();
                if ($genres) {
                    Cache::put($genresCacheKey, $genres, $this->getCacheDuration('genres'));
                    $results['genres'] = 1;
                }
            }

            // Warm celebrity data
            $this->warmCelebrityData();
            $results['celebrities'] = 1;

            return $results;
        } catch (\Exception $e) {
            Log::error('Cache warming error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Warm celebrity data with longer cache
     */
    private function warmCelebrityData()
    {
        $celebrityIds = [
            1, 2, 3, 500, 1461, 6193, 1892, 2231, 6968, 10297,
            1245, 18918, 51329, 17051, 5293, 8784, 934, 1136, 13240, 62
        ];

        foreach (array_chunk($celebrityIds, 5) as $chunk) {
            foreach ($chunk as $id) {
                $cacheKey = "tmdb_person_{$id}";
                if (!Cache::has($cacheKey)) {
                    $person = $this->movieService->getPersonDetails($id);
                    if ($person) {
                        Cache::put($cacheKey, $person, $this->getCacheDuration('person'));
                    }
                }
            }
            // Small delay to avoid rate limiting
            usleep(100000); // 0.1 second
        }
    }

    /**
     * Smart cache key generation with parameters
     */
    public function generateCacheKey($prefix, $params = [])
    {
        if (empty($params)) {
            return $prefix;
        }
        
        ksort($params);
        $paramString = http_build_query($params);
        return $prefix . '_' . md5($paramString);
    }

    /**
     * Batch update cache for multiple related items
     */
    public function batchCacheUpdate($items, $cachePrefix, $cacheDuration = null)
    {
        $duration = $cacheDuration ?? $this->getCacheDuration('default');
        
        foreach ($items as $key => $data) {
            $cacheKey = $cachePrefix . '_' . $key;
            Cache::put($cacheKey, $data, $duration);
        }
    }

    /**
     * Get cached data with fallback
     */
    public function getCachedOrFetch($cacheKey, $fallbackCallback, $duration = null)
    {
        return Cache::remember($cacheKey, $duration ?? $this->getCacheDuration('default'), $fallbackCallback);
    }

    /**
     * Preload homepage data
     */
    public function preloadHomepageData()
    {
        $data = [];
        
        // Popular movies
        $data['popular_movies'] = $this->getCachedOrFetch(
            'homepage_popular_movies',
            fn() => $this->movieService->getPopularMovies(1),
            $this->getCacheDuration('popular')
        );

        // Trending movies
        $data['trending_movies'] = $this->getCachedOrFetch(
            'homepage_trending_movies',
            fn() => $this->movieService->getTrendingMovies(1),
            $this->getCacheDuration('trending')
        );

        // Top rated movies
        $data['top_rated_movies'] = $this->getCachedOrFetch(
            'homepage_top_rated_movies',
            fn() => $this->movieService->getTopRatedMovies(1),
            $this->getCacheDuration('top_rated')
        );

        return $data;
    }

    /**
     * Clear expired cache selectively
     */
    public function clearExpiredCache($pattern = null)
    {
        if ($pattern) {
            // Clear specific pattern
            $keys = Cache::getRedis()->keys("*{$pattern}*");
            foreach ($keys as $key) {
                Cache::forget(str_replace(config('cache.prefix') . ':', '', $key));
            }
        }
    }
}