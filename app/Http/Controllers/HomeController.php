<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MovieService;
use App\Services\TVShowService;
use App\Services\NewsService;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    protected $movieService;
    protected $tvShowService;
    protected $newsService;

    public function __construct(MovieService $movieService, TVShowService $tvShowService, NewsService $newsService)
    {
        $this->movieService = $movieService;
        $this->tvShowService = $tvShowService;
        $this->newsService = $newsService;
    }

    /**
     * Display the homepage with featured movies from various categories
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Fetch different categories of movies for the homepage
            $trendingMovies = $this->movieService->getTrendingMovies(1);
            $popularMovies = $this->movieService->getPopularMovies(1);
            $upcomingMovies = $this->movieService->getUpcomingMovies(1);
            $topRatedMovies = $this->movieService->getTopRatedMovies(1);
            $genres = $this->movieService->getGenres();

            // Fetch TV shows data
            $popularTVShows = $this->tvShowService->getPopularTVShows(1);
            $topRatedTVShows = $this->tvShowService->getTopRatedTVShows(1);
            $airingTodayTVShows = $this->tvShowService->getAiringTodayTVShows(1);
            $onAirTVShows = $this->tvShowService->getOnTheAirTVShows(1);

            // Get in theater trailers
            $inTheaterTrailers = $this->movieService->getInTheaterTrailers(6);

            // Get random movie wallpapers
            $randomWallpapers = $this->getRandomMovieWallpapers();

            // Get latest news for the news section (cached hourly)
            $newsResponse = $this->newsService->getMovieNews(1, 10);
            $latestNews = collect($newsResponse['articles'] ?? [])->toArray();

            // Get spotlight celebrities data
            $spotlightCelebrities = $this->getSpotlightCelebrities();

            // Prepare data for the view
            $data = [
                'trending' => $this->prepareMoviesData($trendingMovies),
                'popular' => $this->prepareMoviesData($popularMovies),
                'upcoming' => $this->prepareMoviesData($upcomingMovies),
                'topRated' => $this->prepareMoviesData($topRatedMovies),
                'genres' => $genres['genres'] ?? [],
                'randomWallpaper' => $randomWallpapers,
                'inTheaterTrailers' => $inTheaterTrailers,
                'latestNews' => $latestNews,
                'spotlightCelebrities' => $spotlightCelebrities,
                // TV Shows data with correct variable names for the homepage
                'popularTVShows' => $this->tvShowService->prepareTVShowsData($popularTVShows),
                'topRatedTVShows' => $this->tvShowService->prepareTVShowsData($topRatedTVShows),
                'airingTodayTVShows' => $this->tvShowService->prepareTVShowsData($airingTodayTVShows),
                'onTheAirTVShows' => $this->tvShowService->prepareTVShowsData($onAirTVShows),
                'error' => null
            ];

            // Add featured movie for hero section (first trending movie)
            $data['featuredMovie'] = !empty($data['trending']) ? $data['trending'][0] : null;

            return view('index_main', $data);

        } catch (\Exception $e) {
            // Log the error
            Log::error('HomeController@index error: ' . $e->getMessage());

            // Return view with error state
            return view('index_main', [
                'trending' => [],
                'popular' => [],
                'upcoming' => [],
                'topRated' => [],
                'genres' => [],
                'featuredMovie' => null,
                'randomWallpaper' => $this->getFallbackWallpaper(),
                'latestNews' => [],
                // TV Shows fallback data
                'popularTVShows' => [],
                'topRatedTVShows' => [],
                'airingTodayTVShows' => [],
                'onTheAirTVShows' => [],
                'error' => 'Unable to load movie data at the moment. Please try again later.'
            ]);
        }
    }

        /**
     * Prepare movies data for display, extracting required fields
     *
     * @param array $moviesResponse
     * @return array
     */
    private function prepareMoviesData($moviesResponse)
    {
        if (!isset($moviesResponse['results']) || !is_array($moviesResponse['results'])) {
            return [];
        }

        return array_map(function ($movie) {
            return [
                'id' => $movie['id'] ?? null,
                'title' => $movie['title'] ?? 'Unknown Title',
                'poster_path' => $movie['poster_path'] ?? null,
                'backdrop_path' => $movie['backdrop_path'] ?? null,
                'overview' => $movie['overview'] ?? '',
                'release_date' => $movie['release_date'] ?? '',
                'vote_average' => $movie['vote_average'] ?? 0,
                'genre_ids' => $movie['genre_ids'] ?? [],
                'poster_url' => $movie['poster_path'] 
                    ? "https://image.tmdb.org/t/p/w500" . $movie['poster_path'] 
                    : null,
                'backdrop_url' => $movie['backdrop_path'] 
                    ? "https://image.tmdb.org/t/p/w1280" . $movie['backdrop_path'] 
                    : null,
            ];
        }, $moviesResponse['results']);
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
     * Get spotlight celebrities for homepage sidebar
     *
     * @return array
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
     *
     * @param string $department
     * @return string
     */
    private function formatDepartment($department)
    {
        $departmentMap = [
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

        return $departmentMap[$department] ?? $department;
    }

    /**
     * Handle search from homepage and other sections
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $type = $request->input('type', 'movies');

        if (empty($query)) {
            return redirect()->route('home')->with('error', 'Please enter a search query.');
        }

        // Redirect to appropriate search page based on type
        switch ($type) {
            case 'tvshows':
                return redirect()->route('tv.search', ['q' => $query]);
            case 'movies':
            default:
                return redirect()->route('movies.search', ['q' => $query]);
        }
    }
}