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

            // Prepare data for the view
            $data = [
                'trending' => $this->prepareMoviesData($trendingMovies),
                'popular' => $this->prepareMoviesData($popularMovies),
                'upcoming' => $this->prepareMoviesData($upcomingMovies),
                'topRated' => $this->prepareMoviesData($topRatedMovies),
                'genres' => $genres['genres'] ?? [],
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
                'error' => 'Unable to load movie data at the moment. Please try again later.'
            ]);
        }
    }

    /**
     * Prepare movie data for the view
     *
     * @param array|null $apiResponse
     * @return array
     */
    private function prepareMoviesData($apiResponse)
    {
        if (!$apiResponse || !isset($apiResponse['results'])) {
            return [];
        }

        // Return first 12 movies for homepage display
        return array_slice($apiResponse['results'], 0, 12);
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