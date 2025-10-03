<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        return view('movies.show', [
            'movie' => $movieDetails,
            'credits' => $credits,
            'images' => $images,
            'videos' => $videos,
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
}