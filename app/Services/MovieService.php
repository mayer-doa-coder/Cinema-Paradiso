<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MovieService
{
    private $apiKey;
    private $baseUrl;
    private $imageBaseUrl;
    private $cacheDuration;
    
    public function __construct()
    {
        $this->apiKey = config('services.tmdb.api_key');
        $this->baseUrl = config('services.tmdb.base_url');
        $this->imageBaseUrl = config('services.tmdb.image_base_url');
        $this->cacheDuration = config('services.tmdb.cache_duration', 3600);
    }

    /**
     * Search for movies by query
     */
    public function searchMovies($query, $page = 1)
    {
        $cacheKey = "tmdb_search_{$query}_page_{$page}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($query, $page) {
            try {
                $response = Http::get("{$this->baseUrl}/search/movie", [
                    'api_key' => $this->apiKey,
                    'query' => $query,
                    'page' => $page,
                    'include_adult' => false,
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error('TMDb API Error: ' . $response->body());
                return null;
            } catch (\Exception $e) {
                Log::error('TMDb API Exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Get popular movies
     */
    public function getPopularMovies($page = 1)
    {
        $cacheKey = "tmdb_popular_page_{$page}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($page) {
            try {
                $response = Http::get("{$this->baseUrl}/movie/popular", [
                    'api_key' => $this->apiKey,
                    'page' => $page,
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error('TMDb API Error: ' . $response->body());
                return null;
            } catch (\Exception $e) {
                Log::error('TMDb API Exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Get top rated movies
     */
    public function getTopRatedMovies($page = 1)
    {
        $cacheKey = "tmdb_top_rated_page_{$page}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($page) {
            try {
                $response = Http::get("{$this->baseUrl}/movie/top_rated", [
                    'api_key' => $this->apiKey,
                    'page' => $page,
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error('TMDb API Error: ' . $response->body());
                return null;
            } catch (\Exception $e) {
                Log::error('TMDb API Exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Get upcoming movies
     */
    public function getUpcomingMovies($page = 1)
    {
        $cacheKey = "tmdb_upcoming_page_{$page}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($page) {
            try {
                $response = Http::get("{$this->baseUrl}/movie/upcoming", [
                    'api_key' => $this->apiKey,
                    'page' => $page,
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error('TMDb API Error: ' . $response->body());
                return null;
            } catch (\Exception $e) {
                Log::error('TMDb API Exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Get trending movies
     */
    public function getTrendingMovies($page = 1)
    {
        $cacheKey = "tmdb_trending_page_{$page}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($page) {
            try {
                $response = Http::get("{$this->baseUrl}/trending/movie/week", [
                    'api_key' => $this->apiKey,
                    'page' => $page,
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error('TMDb API Error: ' . $response->body());
                return null;
            } catch (\Exception $e) {
                Log::error('TMDb API Exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Get movie details by ID
     */
    public function getMovieDetails($movieId)
    {
        $cacheKey = "tmdb_movie_{$movieId}_details";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($movieId) {
            try {
                $response = Http::get("{$this->baseUrl}/movie/{$movieId}", [
                    'api_key' => $this->apiKey,
                    'append_to_response' => 'videos,images,credits,reviews,recommendations',
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error('TMDb API Error: ' . $response->body());
                return null;
            } catch (\Exception $e) {
                Log::error('TMDb API Exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Get movie cast and crew
     */
    public function getMovieCredits($movieId)
    {
        $cacheKey = "tmdb_movie_{$movieId}_credits";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($movieId) {
            try {
                $response = Http::get("{$this->baseUrl}/movie/{$movieId}/credits", [
                    'api_key' => $this->apiKey,
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error('TMDb API Error: ' . $response->body());
                return null;
            } catch (\Exception $e) {
                Log::error('TMDb API Exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Get movie images
     */
    public function getMovieImages($movieId)
    {
        $cacheKey = "tmdb_movie_{$movieId}_images";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($movieId) {
            try {
                $response = Http::get("{$this->baseUrl}/movie/{$movieId}/images", [
                    'api_key' => $this->apiKey,
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error('TMDb API Error: ' . $response->body());
                return null;
            } catch (\Exception $e) {
                Log::error('TMDb API Exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Get movie trailers and videos
     */
    public function getMovieVideos($movieId)
    {
        $cacheKey = "tmdb_movie_{$movieId}_videos";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($movieId) {
            try {
                $response = Http::get("{$this->baseUrl}/movie/{$movieId}/videos", [
                    'api_key' => $this->apiKey,
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error('TMDb API Error: ' . $response->body());
                return null;
            } catch (\Exception $e) {
                Log::error('TMDb API Exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Build full image URL
     */
    public function getImageUrl($path, $size = 'w500')
    {
        if (!$path) {
            return asset('images/uploads/no-image-available.jpg'); // Fallback image
        }
        
        return "{$this->imageBaseUrl}/{$size}{$path}";
    }

    /**
     * Build YouTube trailer URL
     */
    public function getYouTubeUrl($key)
    {
        return "https://www.youtube.com/watch?v={$key}";
    }

    /**
     * Get movie genres
     */
    public function getGenres()
    {
        $cacheKey = "tmdb_genres";
        
        return Cache::remember($cacheKey, 86400, function () { // Cache for 24 hours
            try {
                $response = Http::get("{$this->baseUrl}/genre/movie/list", [
                    'api_key' => $this->apiKey,
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error('TMDb API Error: ' . $response->body());
                return null;
            } catch (\Exception $e) {
                Log::error('TMDb API Exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Discover movies by genre
     */
    public function discoverMoviesByGenre($genreId, $page = 1)
    {
        $cacheKey = "tmdb_discover_genre_{$genreId}_page_{$page}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($genreId, $page) {
            try {
                $response = Http::get("{$this->baseUrl}/discover/movie", [
                    'api_key' => $this->apiKey,
                    'with_genres' => $genreId,
                    'page' => $page,
                    'sort_by' => 'popularity.desc',
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error('TMDb API Error: ' . $response->body());
                return null;
            } catch (\Exception $e) {
                Log::error('TMDb API Exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Discover movies with filters
     */
    public function discoverMovies($filters = [], $page = 1)
    {
        $cacheKey = "tmdb_discover_" . md5(json_encode($filters)) . "_page_{$page}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($filters, $page) {
            try {
                $params = [
                    'api_key' => $this->apiKey,
                    'page' => $page,
                    'sort_by' => $filters['sort_by'] ?? 'popularity.desc',
                ];

                // Add genre filter
                if (!empty($filters['genre'])) {
                    $params['with_genres'] = $filters['genre'];
                }

                // Add year filter
                if (!empty($filters['year'])) {
                    $params['primary_release_year'] = $filters['year'];
                }

                // Add rating filter (minimum vote average)
                if (!empty($filters['rating'])) {
                    $params['vote_average.gte'] = $filters['rating'];
                    $params['vote_count.gte'] = 100; // Ensure movies have enough votes
                }

                $response = Http::get("{$this->baseUrl}/discover/movie", $params);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error('TMDb API Error: ' . $response->body());
                return null;
            } catch (\Exception $e) {
                Log::error('TMDb API Exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Format runtime (minutes) to hours and minutes
     */
    public function formatRuntime($minutes)
    {
        if (!$minutes) return 'N/A';
        
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        
        if ($hours > 0) {
            return $hours . 'h ' . $mins . 'm';
        } else {
            return $mins . 'm';
        }
    }

    /**
     * Format release date
     */
    public function formatReleaseDate($date)
    {
        if (!$date) return 'TBA';
        
        return date('F j, Y', strtotime($date));
    }

    /**
     * Get rating stars (out of 5)
     */
    public function getRatingStars($voteAverage)
    {
        if (!$voteAverage) return 0;
        
        return round($voteAverage / 2, 1); // Convert from 10-point to 5-point scale
    }
}