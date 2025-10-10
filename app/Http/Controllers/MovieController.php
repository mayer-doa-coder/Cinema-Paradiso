<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\MovieService;

class MovieController extends Controller
{
    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    /**
     * Display the main movies page with popular movies
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $category = $request->get('category', 'popular');
        
        // Get filter parameters
        $genre = $request->get('genre');
        $year = $request->get('year');
        $rating = $request->get('rating');
        $sortBy = $request->get('sort_by', 'popularity.desc');
        
        $moviesData = null;
        $title = 'Popular Movies';
        
        // If filters are applied, use discover endpoint
        if ($genre || $year || $rating) {
            $filters = [
                'genre' => $genre,
                'year' => $year,
                'rating' => $rating,
                'sort_by' => $sortBy,
            ];
            $moviesData = $this->movieService->discoverMovies($filters, $page);
            $title = 'Filtered Movies';
        } else {
            // Otherwise use category-based endpoints
            switch ($category) {
                case 'top-rated':
                    $moviesData = $this->movieService->getTopRatedMovies($page);
                    $title = 'Top Rated Movies';
                    break;
                case 'upcoming':
                    $moviesData = $this->movieService->getUpcomingMovies($page);
                    $title = 'Upcoming Movies';
                    break;
                case 'trending':
                    $moviesData = $this->movieService->getTrendingMovies($page);
                    $title = 'Trending Movies';
                    break;
                default:
                    $moviesData = $this->movieService->getPopularMovies($page);
                    break;
            }
        }

        if (!$moviesData) {
            return view('movies.index', [
                'movies' => [],
                'current_page' => 1,
                'total_pages' => 1,
                'title' => $title,
                'category' => $category,
                'genres' => [],
                'selectedGenre' => $genre,
                'selectedYear' => $year,
                'selectedRating' => $rating,
                'error' => 'Unable to load movies at the moment. Please try again later.'
            ]);
        }

        // Get genres for filter dropdown
        $genresData = $this->movieService->getGenres();
        $genres = $genresData['genres'] ?? [];

        // Get random movie wallpaper
        $randomWallpaper = $this->getRandomMovieWallpapers();

        // Get celebrities for sidebar
        $spotlightCelebrities = $this->getSpotlightCelebrities();

        return view('movies.index', [
            'movies' => $moviesData['results'] ?? [],
            'current_page' => $moviesData['page'] ?? 1,
            'total_pages' => $moviesData['total_pages'] ?? 1,
            'title' => $title,
            'category' => $category,
            'genres' => $genres,
            'selectedGenre' => $genre,
            'selectedYear' => $year,
            'selectedRating' => $rating,
            'randomWallpaper' => $randomWallpaper,
            'spotlightCelebrities' => $spotlightCelebrities,
            'error' => null
        ]);
    }

    /**
     * Display the movie grid view (legacy route)
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function grid()
    {
        return redirect()->route('movies.index');
    }

    /**
     * Display the movie list view (legacy route)
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function list()
    {
        return redirect()->route('movies.index');
    }

    /**
     * Display the movie search results.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $page = $request->get('page', 1);
        
        if (empty($query)) {
            return redirect()->route('movies.index')->with('error', 'Please enter a search query.');
        }

        $moviesData = $this->movieService->searchMovies($query, $page);
        
        if (!$moviesData) {
            return view('movies.search', [
                'movies' => [],
                'current_page' => 1,
                'total_pages' => 1,
                'query' => $query,
                'error' => 'Unable to search movies at the moment. Please try again later.'
            ]);
        }

        return view('movies.search', [
            'movies' => $moviesData['results'] ?? [],
            'current_page' => $moviesData['page'] ?? 1,
            'total_pages' => $moviesData['total_pages'] ?? 1,
            'query' => $query,
            'error' => null
        ]);
    }

    /**
     * Display a single movie details.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $movieDetails = $this->movieService->getMovieDetails($id);
        
        if (!$movieDetails) {
            abort(404, 'Movie not found or unable to load movie details.');
        }

        // Get additional data
        $credits = $this->movieService->getMovieCredits($id);
        $images = $this->movieService->getMovieImages($id);
        $videos = $this->movieService->getMovieVideos($id);

        // Get high-resolution backdrop/scene image for hero section (prioritize actual movie scenes)
        $heroBackdrop = null;
        
        // First, try to get a high-quality backdrop from the images collection (actual movie scenes)
        if ($images && isset($images['backdrops']) && !empty($images['backdrops'])) {
            // Filter and sort backdrops to get the best quality movie scene
            $backdrops = collect($images['backdrops'])
                ->filter(function($backdrop) {
                    // Prioritize landscape oriented images (movie scenes are typically widescreen)
                    // Also ensure minimum quality thresholds
                    return $backdrop['width'] >= 1280 && 
                           $backdrop['height'] >= 720 &&
                           ($backdrop['width'] / $backdrop['height']) >= 1.5;
                })
                ->sortByDesc(function($backdrop) {
                    // Heavily weight by vote average (user-selected best scenes) and resolution
                    return (($backdrop['vote_average'] ?? 0) * 10) + ($backdrop['width'] / 1000);
                });
            
            if ($backdrops->isNotEmpty()) {
                $bestBackdrop = $backdrops->first();
                $heroBackdrop = $this->movieService->getImageUrl($bestBackdrop['file_path'], 'original');
                
                // Debug log to confirm we're using a scene, not a poster
                Log::info("Using movie scene backdrop for hero: {$bestBackdrop['file_path']}, Resolution: {$bestBackdrop['width']}x{$bestBackdrop['height']}, Rating: " . ($bestBackdrop['vote_average'] ?? 'N/A'));
            }
        }
        
        // Fallback to main movie backdrop (also a scene, not poster) if no collection scenes available
        // NOTE: backdrop_path is always a movie scene, never a poster
        if (!$heroBackdrop && isset($movieDetails['backdrop_path'])) {
            $heroBackdrop = $this->movieService->getImageUrl($movieDetails['backdrop_path'], 'original');
            Log::info("Using main movie backdrop as fallback: {$movieDetails['backdrop_path']}");
        }
        
        // Last resort: use a default hero image (never use poster_path for hero section)
        if (!$heroBackdrop) {
            Log::warning("No movie scene available for movie ID: {$id}, using default hero background");
        }

        return view('movies.show', [
            'movie' => $movieDetails,
            'credits' => $credits,
            'images' => $images,
            'videos' => $videos,
            'heroBackdrop' => $heroBackdrop,
            'error' => null
        ]);
    }

    /**
     * Display movies by genre
     *
     * @param \Illuminate\Http\Request $request
     * @param int $genreId
     * @return \Illuminate\View\View
     */
    public function byGenre(Request $request, $genreId)
    {
        $page = $request->get('page', 1);
        $moviesData = $this->movieService->discoverMoviesByGenre($genreId, $page);
        $genres = $this->movieService->getGenres();
        
        $genreName = 'Movies';
        if ($genres && isset($genres['genres'])) {
            foreach ($genres['genres'] as $genre) {
                if ($genre['id'] == $genreId) {
                    $genreName = $genre['name'] . ' Movies';
                    break;
                }
            }
        }

        if (!$moviesData) {
            return view('movies.genre', [
                'movies' => [],
                'current_page' => 1,
                'total_pages' => 1,
                'genre_id' => $genreId,
                'genre_name' => $genreName,
                'error' => 'Unable to load movies at the moment. Please try again later.'
            ]);
        }

        return view('movies.genre', [
            'movies' => $moviesData['results'] ?? [],
            'current_page' => $moviesData['page'] ?? 1,
            'total_pages' => $moviesData['total_pages'] ?? 1,
            'genre_id' => $genreId,
            'genre_name' => $genreName,
            'error' => null
        ]);
    }

    /**
     * Get movie cast and crew (AJAX endpoint)
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCredits($id)
    {
        $credits = $this->movieService->getMovieCredits($id);
        
        if (!$credits) {
            return response()->json(['error' => 'Unable to load cast and crew information.'], 500);
        }

        return response()->json($credits);
    }

    /**
     * Get movie images (AJAX endpoint)
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getImages($id)
    {
        $images = $this->movieService->getMovieImages($id);
        
        if (!$images) {
            return response()->json(['error' => 'Unable to load movie images.'], 500);
        }

        return response()->json($images);
    }

    /**
     * Get movie videos/trailers (AJAX endpoint)
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVideos($id)
    {
        $videos = $this->movieService->getMovieVideos($id);
        
        if (!$videos) {
            return response()->json(['error' => 'Unable to load movie videos.'], 500);
        }

        return response()->json($videos);
    }

    /**
     * Get random movie wallpapers from different categories
     *
     * @return array
     */
    private function getRandomMovieWallpapers()
    {
        try {
            // Get movies from different categories to ensure variety
            $categories = [
                $this->movieService->getPopularMovies(1),
                $this->movieService->getTrendingMovies(1),
                $this->movieService->getTopRatedMovies(1),
                $this->movieService->getUpcomingMovies(1)
            ];

            $allWallpapers = [];

            // Collect all movies with backdrop images
            foreach ($categories as $category) {
                if (isset($category['results'])) {
                    foreach ($category['results'] as $movie) {
                        if (!empty($movie['backdrop_path'])) {
                            $allWallpapers[] = [
                                'id' => $movie['id'],
                                'title' => $movie['title'],
                                'backdrop_path' => $movie['backdrop_path'],
                                'backdrop_url' => "https://image.tmdb.org/t/p/w780" . $movie['backdrop_path'],
                                'overview' => substr($movie['overview'] ?? '', 0, 100) . '...'
                            ];
                        }
                    }
                }
            }

            // Remove duplicates based on movie ID
            $uniqueWallpapers = [];
            $seenIds = [];
            foreach ($allWallpapers as $wallpaper) {
                if (!in_array($wallpaper['id'], $seenIds)) {
                    $uniqueWallpapers[] = $wallpaper;
                    $seenIds[] = $wallpaper['id'];
                }
            }

            // Shuffle and return a random selection
            shuffle($uniqueWallpapers);
            return array_slice($uniqueWallpapers, 0, 1)[0] ?? $this->getFallbackWallpaper();

        } catch (\Exception $e) {
            Log::error('Error getting random wallpapers: ' . $e->getMessage());
            return $this->getFallbackWallpaper();
        }
    }

    /**
     * Get fallback wallpaper when API fails
     *
     * @return array
     */
    private function getFallbackWallpaper()
    {
        return [
            'id' => null,
            'title' => 'Cinema Paradiso',
            'backdrop_path' => null,
            'backdrop_url' => asset('images/cinema_paradiso.png'),
            'overview' => 'Welcome to Cinema Paradiso - Your gateway to the world of movies.'
        ];
    }

    /**
     * Get spotlight celebrities for sidebar
     */
    private function getSpotlightCelebrities()
    {
        try {
            // Curated list of most popular celebrities from 2010s with their TMDB IDs
            $popular2010sCelebrities = [
                1892,   // Matt Damon
                6193,   // Leonardo DiCaprio
                2231,   // Samuel L. Jackson
                3894,   // Christian Bale
                1245,   // Scarlett Johansson
                6968,   // Hugh Jackman
                10980,  // Daniel Craig
                3223,   // Robert Downey Jr.
                10859,  // Ryan Gosling
                5472,   // Sandra Bullock
                1204,   // Julia Roberts
                8784,   // Daniel Radcliffe
                3061,   // Ewan McGregor
                2888,   // Will Smith
                4724,   // Kevin Costner
                8891,   // John Travolta
                2963,   // Nicolas Cage
                1461,   // George Clooney
                139,    // Jamie Foxx
                2231,   // Samuel L. Jackson
                1892,   // Matt Damon
                62,     // Bruce Willis
                2524,   // Tom Hardy
                73421,  // Joaquin Phoenix
                287,    // Brad Pitt
                6384,   // Keanu Reeves
                500,    // Tom Cruise
                31,     // Tom Hanks
                1158,   // Al Pacino
                380,    // Robert De Niro
                5081,   // Emily Blunt
                72129,  // Jennifer Lawrence
                1813,   // Anne Hathaway
                524,    // Natalie Portman
                5064,   // Meryl Streep
                5293,   // Willem Dafoe
            ];

            // Shuffle and select 4 random celebrities from the curated list
            shuffle($popular2010sCelebrities);
            $selectedIds = array_slice($popular2010sCelebrities, 0, 4);

            $spotlightCelebrities = [];
            
            foreach ($selectedIds as $personId) {
                try {
                    // Get person details from TMDB
                    $personData = $this->movieService->getPersonDetails($personId);
                    
                    if ($personData && !empty($personData['name'])) {
                        $spotlightCelebrities[] = [
                            'id' => $personData['id'],
                            'name' => $personData['name'],
                            'profile_path' => $personData['profile_path'],
                            'profile_url' => $this->movieService->getPersonImageUrl($personData['profile_path'], 'w185'),
                            'known_for_department' => $this->formatDepartment($personData['known_for_department'] ?? 'Acting'),
                            'popularity' => $personData['popularity'] ?? 0,
                        ];
                    }
                } catch (\Exception $e) {
                    // Continue to next celebrity if one fails
                    Log::warning('Failed to fetch celebrity ' . $personId . ': ' . $e->getMessage());
                    continue;
                }
            }

            // If we don't have enough celebrities, fill with fallback
            while (count($spotlightCelebrities) < 4) {
                $spotlightCelebrities[] = [
                    'id' => 0,
                    'name' => 'Hollywood Star',
                    'profile_path' => null,
                    'profile_url' => asset('images/uploads/ava1.jpg'),
                    'known_for_department' => 'Actor',
                    'popularity' => 0,
                ];
            }
            
            return array_slice($spotlightCelebrities, 0, 4);
            
        } catch (\Exception $e) {
            Log::error('Error fetching spotlight celebrities: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Format department name for display
     */
    private function formatDepartment($department)
    {
        $departments = [
            'Acting' => 'Actor',
            'Directing' => 'Director',
            'Writing' => 'Writer',
            'Production' => 'Producer',
            'Sound' => 'Music',
            'Camera' => 'Cinematographer',
            'Editing' => 'Editor',
            'Art' => 'Art Director',
            'Costume & Make-Up' => 'Designer',
            'Crew' => 'Crew',
        ];

        return $departments[$department] ?? $department;
    }
}