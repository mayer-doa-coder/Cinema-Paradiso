<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TVShowService
{
    private $baseUrl;
    private $apiKey;
    private $imageBaseUrl;
    private $cacheDuration = 3600; // 1 hour

    public function __construct()
    {
        $this->baseUrl = env('TMDB_BASE_URL', 'https://api.themoviedb.org/3');
        $this->apiKey = env('TMDB_API_KEY');
        $this->imageBaseUrl = env('TMDB_IMAGE_BASE_URL', 'https://image.tmdb.org/t/p');
    }

    /**
     * Get popular TV shows
     */
    public function getPopularTVShows($page = 1)
    {
        $cacheKey = "tmdb_popular_tv_shows_page_{$page}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($page) {
            try {
                $response = Http::get("{$this->baseUrl}/tv/popular", [
                    'api_key' => $this->apiKey,
                    'page' => $page,
                    'language' => 'en-US'
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
     * Get top rated TV shows
     */
    public function getTopRatedTVShows($page = 1)
    {
        $cacheKey = "tmdb_top_rated_tv_shows_page_{$page}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($page) {
            try {
                $response = Http::get("{$this->baseUrl}/tv/top_rated", [
                    'api_key' => $this->apiKey,
                    'page' => $page,
                    'language' => 'en-US'
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
     * Get trending TV shows
     */
    public function getTrendingTVShows($page = 1)
    {
        $cacheKey = "tmdb_trending_tv_shows_page_{$page}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($page) {
            try {
                $response = Http::get("{$this->baseUrl}/trending/tv/week", [
                    'api_key' => $this->apiKey,
                    'page' => $page,
                    'language' => 'en-US'
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
     * Get TV shows airing today
     */
    public function getAiringTodayTVShows($page = 1)
    {
        $cacheKey = "tmdb_airing_today_tv_shows_page_{$page}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($page) {
            try {
                $response = Http::get("{$this->baseUrl}/tv/airing_today", [
                    'api_key' => $this->apiKey,
                    'page' => $page,
                    'language' => 'en-US'
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
     * Get TV shows on the air
     */
    public function getOnTheAirTVShows($page = 1)
    {
        $cacheKey = "tmdb_on_air_tv_shows_page_{$page}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($page) {
            try {
                $response = Http::get("{$this->baseUrl}/tv/on_the_air", [
                    'api_key' => $this->apiKey,
                    'page' => $page,
                    'language' => 'en-US'
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
     * Get TV show details
     */
    public function getTVShowDetails($tvId)
    {
        $cacheKey = "tmdb_tv_show_{$tvId}_details";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($tvId) {
            try {
                $response = Http::get("{$this->baseUrl}/tv/{$tvId}", [
                    'api_key' => $this->apiKey,
                    'append_to_response' => 'videos,images,credits,reviews,recommendations,similar',
                    'language' => 'en-US'
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
     * Search TV shows
     */
    public function searchTVShows($query, $page = 1)
    {
        $cacheKey = "tmdb_search_tv_" . md5($query) . "_page_{$page}";
        
        return Cache::remember($cacheKey, 1800, function () use ($query, $page) {
            try {
                $response = Http::get("{$this->baseUrl}/search/tv", [
                    'api_key' => $this->apiKey,
                    'query' => $query,
                    'page' => $page,
                    'language' => 'en-US'
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
     * Get TV show videos
     */
    public function getTVShowVideos($tvId)
    {
        $cacheKey = "tmdb_tv_show_{$tvId}_videos";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($tvId) {
            try {
                $response = Http::get("{$this->baseUrl}/tv/{$tvId}/videos", [
                    'api_key' => $this->apiKey,
                    'language' => 'en-US'
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
     * Get TV show credits
     */
    public function getTVShowCredits($tvId)
    {
        $cacheKey = "tmdb_tv_show_{$tvId}_credits";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($tvId) {
            try {
                $response = Http::get("{$this->baseUrl}/tv/{$tvId}/credits", [
                    'api_key' => $this->apiKey,
                    'language' => 'en-US'
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
     * Get TV genres
     */
    public function getTVGenres()
    {
        $cacheKey = "tmdb_tv_genres";
        
        return Cache::remember($cacheKey, 86400, function () { // Cache for 24 hours
            try {
                $response = Http::get("{$this->baseUrl}/genre/tv/list", [
                    'api_key' => $this->apiKey,
                    'language' => 'en-US'
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
     * Get TV shows by genre
     */
    public function getTVShowsByGenre($genreId, $page = 1)
    {
        $cacheKey = "tmdb_tv_shows_genre_{$genreId}_page_{$page}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($genreId, $page) {
            try {
                $response = Http::get("{$this->baseUrl}/discover/tv", [
                    'api_key' => $this->apiKey,
                    'with_genres' => $genreId,
                    'page' => $page,
                    'sort_by' => 'popularity.desc',
                    'language' => 'en-US'
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
     * Get TV season details
     */
    public function getTVSeasonDetails($tvId, $seasonNumber)
    {
        $cacheKey = "tmdb_tv_show_{$tvId}_season_{$seasonNumber}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($tvId, $seasonNumber) {
            try {
                $response = Http::get("{$this->baseUrl}/tv/{$tvId}/season/{$seasonNumber}", [
                    'api_key' => $this->apiKey,
                    'language' => 'en-US'
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
     * Get image URL for different sizes
     */
    public function getImageUrl($imagePath, $size = 'w500')
    {
        if (!$imagePath) {
            return asset('images/uploads/default-movie-poster.jpg');
        }
        
        return $this->imageBaseUrl . '/' . $size . $imagePath;
    }

    /**
     * Format TV show data for display
     */
    public function formatTVShowData($tvShow)
    {
        return [
            'id' => $tvShow['id'],
            'name' => $tvShow['name'],
            'original_name' => $tvShow['original_name'] ?? $tvShow['name'],
            'overview' => $tvShow['overview'] ?? '',
            'poster_path' => $tvShow['poster_path'],
            'backdrop_path' => $tvShow['backdrop_path'],
            'first_air_date' => $tvShow['first_air_date'] ?? '',
            'vote_average' => round($tvShow['vote_average'], 1),
            'vote_count' => $tvShow['vote_count'] ?? 0,
            'genre_ids' => $tvShow['genre_ids'] ?? [],
            'origin_country' => $tvShow['origin_country'] ?? [],
            'original_language' => $tvShow['original_language'] ?? 'en',
            'popularity' => $tvShow['popularity'] ?? 0,
            'poster_url' => $this->getImageUrl($tvShow['poster_path']),
            'backdrop_url' => $this->getImageUrl($tvShow['backdrop_path'], 'w780'),
            'thumb_url' => $this->getImageUrl($tvShow['poster_path'], 'w300'),
        ];
    }

    /**
     * Prepare TV shows data for views
     */
    public function prepareTVShowsData($tvShowsResponse)
    {
        if (!$tvShowsResponse || !isset($tvShowsResponse['results'])) {
            return [];
        }

        $formattedShows = [];
        foreach ($tvShowsResponse['results'] as $tvShow) {
            $formattedShows[] = $this->formatTVShowData($tvShow);
        }

        return $formattedShows;
    }
}