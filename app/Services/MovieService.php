<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\ApiRateLimiter;

class MovieService
{
    private $apiKey;
    private $baseUrl;
    private $imageBaseUrl;
    private $cacheDuration;
    private $rateLimiter;
    
    public function __construct(ApiRateLimiter $rateLimiter)
    {
        $this->apiKey = config('services.tmdb.api_key');
        $this->baseUrl = config('services.tmdb.base_url');
        $this->imageBaseUrl = config('services.tmdb.image_base_url');
        $this->cacheDuration = config('services.tmdb.cache_duration', 3600);
        $this->rateLimiter = $rateLimiter;
    }

    /**
     * Get cache duration for specific data type
     */
    private function getCacheDuration($type)
    {
        $durations = config('services.tmdb.cache_durations', []);
        return $durations[$type] ?? $this->cacheDuration;
    }

    /**
     * Filter results to only include items with valid poster/profile images
     */
    private function filterResultsWithImages($results, $imageField = 'poster_path')
    {
        if (!isset($results['results']) || !is_array($results['results'])) {
            return $results;
        }

        $results['results'] = array_filter($results['results'], function($item) use ($imageField) {
            return !empty($item[$imageField]);
        });

        // Re-index array to maintain sequential keys
        $results['results'] = array_values($results['results']);
        
        return $results;
    }

    /**
     * Filter array of items to only include those with valid images
     */
    private function filterArrayWithImages($items, $imageField = 'poster_path')
    {
        if (!is_array($items)) {
            return $items;
        }

        $filtered = array_filter($items, function($item) use ($imageField) {
            return !empty($item[$imageField]);
        });

        return array_values($filtered);
    }

    /**
     * Search for movies by query
     */
    public function searchMovies($query, $page = 1)
    {
        $cacheKey = "tmdb_search_{$query}_page_{$page}";
        
        return Cache::remember($cacheKey, $this->getCacheDuration('search'), function () use ($query, $page) {
            try {
                $response = $this->rateLimiter->makeRequest("{$this->baseUrl}/search/movie", [
                    'api_key' => $this->apiKey,
                    'query' => $query,
                    'page' => $page,
                    'include_adult' => false,
                ], 'search');

                if ($response->successful()) {
                    $data = $response->json();
                    // Filter out movies without poster images
                    return $this->filterResultsWithImages($data, 'poster_path');
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
        
        return Cache::remember($cacheKey, $this->getCacheDuration('popular'), function () use ($page) {
            try {
                $response = Http::get("{$this->baseUrl}/movie/popular", [
                    'api_key' => $this->apiKey,
                    'page' => $page,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    // Filter out movies without poster images
                    return $this->filterResultsWithImages($data, 'poster_path');
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
        
        return Cache::remember($cacheKey, $this->getCacheDuration('top_rated'), function () use ($page) {
            try {
                $response = Http::get("{$this->baseUrl}/movie/top_rated", [
                    'api_key' => $this->apiKey,
                    'page' => $page,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    // Filter out movies without poster images
                    return $this->filterResultsWithImages($data, 'poster_path');
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
        
        return Cache::remember($cacheKey, $this->getCacheDuration('upcoming'), function () use ($page) {
            try {
                $response = Http::get("{$this->baseUrl}/movie/upcoming", [
                    'api_key' => $this->apiKey,
                    'page' => $page,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    // Filter out movies without poster images
                    return $this->filterResultsWithImages($data, 'poster_path');
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
     * Get now playing movies (in theaters)
     */
    public function getNowPlayingMovies($page = 1)
    {
        $cacheKey = "tmdb_now_playing_page_{$page}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($page) {
            try {
                $response = Http::get("{$this->baseUrl}/movie/now_playing", [
                    'api_key' => $this->apiKey,
                    'page' => $page,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    // Filter out movies without poster or backdrop images
                    return $this->filterResultsWithImages($data, 'backdrop_path');
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
     * Get movie trailers with details for in theater section
     */
    public function getInTheaterTrailers($limit = 6)
    {
        try {
            $nowPlayingMovies = $this->getNowPlayingMovies(1);
            
            if (!$nowPlayingMovies || empty($nowPlayingMovies['results'])) {
                return [];
            }
            
            $trailers = [];
            $count = 0;
            
            foreach ($nowPlayingMovies['results'] as $movie) {
                if ($count >= $limit) break;
                
                // Skip movies with problematic titles or content
                $title = strtolower($movie['title'] ?? '');
                $excludedTitles = ['demon slayer', 'primitive war', 'kimetsu no yaiba'];
                
                $shouldSkip = false;
                foreach ($excludedTitles as $excluded) {
                    if (stripos($title, $excluded) !== false) {
                        $shouldSkip = true;
                        break;
                    }
                }
                
                if ($shouldSkip) {
                    continue;
                }
                
                // Get videos for this movie
                $videos = $this->getMovieVideos($movie['id']);
                
                if ($videos && !empty($videos['results'])) {
                    // Find the best trailer
                    $trailer = $this->findBestTrailer($videos['results']);
                    
                    if ($trailer) {
                        $trailers[] = [
                            'id' => $movie['id'],
                            'title' => $movie['title'],
                            'backdrop_path' => $movie['backdrop_path'],
                            'backdrop_url' => $this->getImageUrl($movie['backdrop_path'], 'w500'),
                            'thumbnail_url' => $this->getImageUrl($movie['backdrop_path'], 'w500'),
                            'video_key' => $trailer['key'],
                            'video_url' => "https://www.youtube.com/embed/{$trailer['key']}",
                            'duration' => $this->formatDuration($trailer),
                            'release_date' => $movie['release_date'] ?? '',
                            'vote_average' => $movie['vote_average'] ?? 0
                        ];
                        $count++;
                    }
                }
            }
            
            return $trailers;
        } catch (\Exception $e) {
            Log::error('Error getting in theater trailers: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Find the best trailer from video results
     */
    private function findBestTrailer($videos)
    {
        // Priority order: Official Trailer > Trailer > Teaser
        $priorities = ['Official Trailer', 'Trailer', 'Teaser', 'Clip'];
        
        foreach ($priorities as $priority) {
            foreach ($videos as $video) {
                if ($video['site'] === 'YouTube' && 
                    $video['type'] === 'Trailer' && 
                    stripos($video['name'], $priority) !== false) {
                    return $video;
                }
            }
        }
        
        // Fallback: any YouTube trailer
        foreach ($videos as $video) {
            if ($video['site'] === 'YouTube' && $video['type'] === 'Trailer') {
                return $video;
            }
        }
        
        return null;
    }

    /**
     * Format video duration (mock duration since API doesn't provide it)
     */
    private function formatDuration($trailer)
    {
        // Generate realistic durations based on trailer type
        $type = $trailer['name'] ?? '';
        
        if (stripos($type, 'teaser') !== false) {
            return rand(30, 90) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);
        } elseif (stripos($type, 'trailer') !== false) {
            return rand(1, 3) . ':' . str_pad(rand(10, 59), 2, '0', STR_PAD_LEFT);
        } else {
            return rand(2, 5) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);
        }
    }

    /**
     * Get trending movies
     */
    public function getTrendingMovies($page = 1)
    {
        $cacheKey = "tmdb_trending_page_{$page}";
        
        return Cache::remember($cacheKey, $this->getCacheDuration('trending'), function () use ($page) {
            try {
                $response = Http::get("{$this->baseUrl}/trending/movie/week", [
                    'api_key' => $this->apiKey,
                    'page' => $page,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    // Filter out movies without poster images
                    return $this->filterResultsWithImages($data, 'poster_path');
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
     * Get movie reviews
     */
    public function getMovieReviews($movieId, $page = 1)
    {
        $cacheKey = "tmdb_movie_{$movieId}_reviews_page_{$page}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($movieId, $page) {
            try {
                $response = Http::get("{$this->baseUrl}/movie/{$movieId}/reviews", [
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
     * Get similar movies based on genre, keywords, and themes
     */
    public function getSimilarMovies($movieId, $page = 1)
    {
        $cacheKey = "tmdb_movie_{$movieId}_similar_page_{$page}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($movieId, $page) {
            try {
                $response = Http::get("{$this->baseUrl}/movie/{$movieId}/similar", [
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
     * Get recommended movies based on viewing history and preferences
     */
    public function getRecommendedMovies($movieId, $page = 1)
    {
        $cacheKey = "tmdb_movie_{$movieId}_recommendations_page_{$page}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($movieId, $page) {
            try {
                $response = Http::get("{$this->baseUrl}/movie/{$movieId}/recommendations", [
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
        
        return Cache::remember($cacheKey, $this->getCacheDuration('genres'), function () { // Cache for 24 hours
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
                    $data = $response->json();
                    // Filter out movies without poster images
                    return $this->filterResultsWithImages($data, 'poster_path');
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
                    $data = $response->json();
                    // Filter out movies without poster images
                    return $this->filterResultsWithImages($data, 'poster_path');
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

    /**
     * Get popular celebrities/people with optimized caching
     */
    public function getPopularPeople($page = 1)
    {
        $cacheKey = "tmdb_popular_people_page_{$page}";
        
        return Cache::remember($cacheKey, $this->cacheDuration * 2, function () use ($page) { // Extended cache time
            try {
                $response = Http::timeout(30)->get("{$this->baseUrl}/person/popular", [ // Added timeout
                    'api_key' => $this->apiKey,
                    'page' => $page,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    // Filter out people without profile images
                    return $this->filterResultsWithImages($data, 'profile_path');
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
     * Search for people/celebrities
     */
    public function searchPeople($query, $page = 1)
    {
        $cacheKey = "tmdb_search_people_{$query}_page_{$page}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($query, $page) {
            try {
                $response = Http::get("{$this->baseUrl}/search/person", [
                    'api_key' => $this->apiKey,
                    'query' => $query,
                    'page' => $page,
                    'include_adult' => false,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    // Filter out people without profile images
                    return $this->filterResultsWithImages($data, 'profile_path');
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
     * Get person details by ID
     */
    public function getPersonDetails($personId)
    {
        $cacheKey = "tmdb_person_{$personId}";
        
        return Cache::remember($cacheKey, $this->cacheDuration * 24, function () use ($personId) {
            try {
                $response = Http::get("{$this->baseUrl}/person/{$personId}", [
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
     * Get person's movie credits
     */
    public function getPersonMovieCredits($personId)
    {
        $cacheKey = "tmdb_person_movies_{$personId}";
        
        return Cache::remember($cacheKey, $this->cacheDuration * 24, function () use ($personId) {
            try {
                $response = Http::get("{$this->baseUrl}/person/{$personId}/movie_credits", [
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
     * Format person profile image URL
     */
    public function getPersonImageUrl($profilePath, $size = 'w185')
    {
        if (!$profilePath) {
            return asset('images/uploads/default-avatar.jpg'); // Fallback image
        }
        
        return "https://image.tmdb.org/t/p/{$size}{$profilePath}";
    }

    /**
     * Get person's known for department formatted
     */
    public function getFormattedDepartment($department)
    {
        $departments = [
            'Acting' => 'Actor',
            'Directing' => 'Director',
            'Writing' => 'Writer',
            'Production' => 'Producer',
            'Sound' => 'Sound Engineer',
            'Camera' => 'Cinematographer',
            'Editing' => 'Editor',
            'Art' => 'Art Director',
            'Costume & Make-Up' => 'Costume Designer',
            'Visual Effects' => 'VFX Artist',
        ];

        return $departments[$department] ?? $department;
    }

    /**
     * Get comprehensive movie data by fetching multiple pages
     * Useful for getting more complete datasets for filtering/sorting
     */
    public function getComprehensiveMovies($category = 'popular', $maxPages = 20)
    {
        $allMovies = [];
        $totalResults = 0;
        $totalPages = 1;

        for ($page = 1; $page <= $maxPages; $page++) {
            $movieData = null;
            
            switch ($category) {
                case 'top-rated':
                    $movieData = $this->getTopRatedMovies($page);
                    break;
                case 'upcoming':
                    $movieData = $this->getUpcomingMovies($page);
                    break;
                case 'trending':
                    $movieData = $this->getTrendingMovies($page);
                    break;
                default:
                    $movieData = $this->getPopularMovies($page);
                    break;
            }

            if (!$movieData || !isset($movieData['results'])) {
                break;
            }

            $allMovies = array_merge($allMovies, $movieData['results']);
            $totalResults = $movieData['total_results'] ?? 0;
            $totalPages = $movieData['total_pages'] ?? 1;

            // Break if we've reached the actual total pages
            if ($page >= $totalPages) {
                break;
            }
        }

        return [
            'results' => $allMovies,
            'total_results' => count($allMovies),
            'total_pages' => ceil(count($allMovies) / 20), // Assuming 20 movies per page for display
            'comprehensive' => true
        ];
    }

    /**
     * Get movies by initial letter (for alphabetical browsing)
     */
    public function getMoviesByLetter($letter, $maxMovies = 500)
    {
        $allMovies = [];
        
        // Fetch from multiple categories to get diverse movie selection
        $categories = ['popular', 'top-rated', 'upcoming', 'trending'];
        
        foreach ($categories as $category) {
            for ($page = 1; $page <= 50; $page++) { // Check more pages
                $movieData = null;
                
                switch ($category) {
                    case 'top-rated':
                        $movieData = $this->getTopRatedMovies($page);
                        break;
                    case 'upcoming':
                        $movieData = $this->getUpcomingMovies($page);
                        break;
                    case 'trending':
                        $movieData = $this->getTrendingMovies($page);
                        break;
                    default:
                        $movieData = $this->getPopularMovies($page);
                        break;
                }

                if (!$movieData || !isset($movieData['results'])) {
                    break;
                }

                foreach ($movieData['results'] as $movie) {
                    if (strtoupper(substr($movie['title'], 0, 1)) === strtoupper($letter)) {
                        $allMovies[] = $movie;
                    }
                }

                // Stop if we have enough movies for this letter
                if (count($allMovies) >= $maxMovies) {
                    break 2; // Break out of both loops
                }
            }
        }

        // Remove duplicates based on movie ID
        $uniqueMovies = [];
        $seenIds = [];
        foreach ($allMovies as $movie) {
            if (!in_array($movie['id'], $seenIds)) {
                $uniqueMovies[] = $movie;
                $seenIds[] = $movie['id'];
            }
        }

        // Sort alphabetically
        usort($uniqueMovies, function($a, $b) {
            return strcmp($a['title'], $b['title']);
        });

        return $uniqueMovies;
    }

    /**
     * Get comprehensive celebrity/people data by fetching multiple pages
     */
    public function getComprehensivePeople($maxPages = 100)
    {
        $allPeople = [];
        $totalResults = 0;
        $totalPages = 1;

        for ($page = 1; $page <= $maxPages; $page++) {
            $peopleData = $this->getPopularPeople($page);
            
            if (!$peopleData || !isset($peopleData['results'])) {
                break;
            }

            $allPeople = array_merge($allPeople, $peopleData['results']);
            $totalResults = $peopleData['total_results'] ?? 0;
            $totalPages = $peopleData['total_pages'] ?? 1;

            // Break if we've reached the actual total pages
            if ($page >= $totalPages) {
                break;
            }
        }

        return [
            'results' => $allPeople,
            'total_results' => count($allPeople),
            'total_pages' => ceil(count($allPeople) / 20), // Assuming 20 people per page for display
            'comprehensive' => true
        ];
    }

    /**
     * Get all available people grouped by first letter
     */
    public function getPeopleByFirstLetter($maxPages = 100)
    {
        $peopleByLetter = [];
        $alphabet = range('A', 'Z');
        
        // Initialize array for each letter
        foreach ($alphabet as $letter) {
            $peopleByLetter[$letter] = [];
        }

        for ($page = 1; $page <= $maxPages; $page++) {
            $peopleData = $this->getPopularPeople($page);
            
            if (!$peopleData || !isset($peopleData['results'])) {
                break;
            }

            foreach ($peopleData['results'] as $person) {
                // Skip people without profile images (already filtered by getPopularPeople)
                if (empty($person['profile_path'])) {
                    continue;
                }
                
                $firstLetter = strtoupper(substr($person['name'], 0, 1));
                if (in_array($firstLetter, $alphabet)) {
                    $peopleByLetter[$firstLetter][] = $person;
                }
            }
        }

        // Sort each letter group alphabetically
        foreach ($peopleByLetter as $letter => $people) {
            usort($peopleByLetter[$letter], function($a, $b) {
                return strcmp($a['name'], $b['name']);
            });
        }

        return $peopleByLetter;
    }

    /**
     * Get celebrities by letter with optimized caching
     * This method is optimized for speed
     */
    public function getCelebritiesByLetterFast($letter, $maxResults = 60)
    {
        $cacheKey = "celebrities_by_letter_{$letter}";
        
        return Cache::remember($cacheKey, $this->cacheDuration * 3, function () use ($letter, $maxResults) {
            $celebrities = [];
            
            // Only fetch first few pages for speed
            for ($page = 1; $page <= 8; $page++) {
                $peopleData = $this->getPopularPeople($page);
                
                if (!$peopleData || !isset($peopleData['results'])) {
                    break;
                }

                foreach ($peopleData['results'] as $person) {
                    // Skip people without profile images (already filtered by getPopularPeople)
                    if (empty($person['profile_path'])) {
                        continue;
                    }
                    
                    if (strtoupper(substr($person['name'], 0, 1)) === strtoupper($letter)) {
                        $celebrities[] = $person;
                        
                        // Stop when we have enough
                        if (count($celebrities) >= $maxResults) {
                            break 2;
                        }
                    }
                }
            }

            // Sort alphabetically
            usort($celebrities, function($a, $b) {
                return strcmp($a['name'], $b['name']);
            });

            return $celebrities;
        });
    }

    /**
     * Get available letters with caching
     */
    public function getAvailableLettersFast()
    {
        $cacheKey = "available_letters_fast";
        
        return Cache::remember($cacheKey, $this->cacheDuration * 6, function () {
            $letters = [];
            $alphabet = range('A', 'Z');
            
            // Only check first few pages for speed
            for ($page = 1; $page <= 3; $page++) {
                $peopleData = $this->getPopularPeople($page);
                
                if (!$peopleData || !isset($peopleData['results'])) {
                    break;
                }

                foreach ($peopleData['results'] as $person) {
                    $firstLetter = strtoupper(substr($person['name'], 0, 1));
                    if (in_array($firstLetter, $alphabet) && !in_array($firstLetter, $letters)) {
                        $letters[] = $firstLetter;
                    }
                }
            }

            // Ensure all letters are available
            $letters = array_unique(array_merge($letters, $alphabet));
            sort($letters);
            
            return $letters;
        });
    }
}