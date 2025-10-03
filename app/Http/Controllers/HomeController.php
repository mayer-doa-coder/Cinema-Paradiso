<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MovieService;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
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

            // Get in theater trailers
            $inTheaterTrailers = $this->movieService->getInTheaterTrailers(6);

            // Get random movie wallpapers
            $randomWallpapers = $this->getRandomMovieWallpapers();

            // Prepare data for the view
            $data = [
                'trending' => $this->prepareMoviesData($trendingMovies),
                'popular' => $this->prepareMoviesData($popularMovies),
                'upcoming' => $this->prepareMoviesData($upcomingMovies),
                'topRated' => $this->prepareMoviesData($topRatedMovies),
                'genres' => $genres['genres'] ?? [],
                'randomWallpaper' => $randomWallpapers,
                'inTheaterTrailers' => $inTheaterTrailers,
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
     * Handle movie search from homepage
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
        if ($type === 'movies') {
            return redirect()->route('movies.search', ['q' => $query]);
        }

        // For now, redirect to movies search for all types
        return redirect()->route('movies.search', ['q' => $query]);
    }
}