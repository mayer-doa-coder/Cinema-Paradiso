<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\MovieService;

class CelebrityController extends Controller
{
    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    /**
     * Display the celebrities page
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $page = $request->get('page', 1);
            $sort = $request->get('sort', 'popularity');
            $search = $request->get('search');

            // Get celebrities data
            if ($search) {
                $celebritiesData = $this->movieService->searchPeople($search, $page);
            } else {
                $celebritiesData = $this->movieService->getPopularPeople($page);
            }

            $celebrities = [];
            $totalResults = 0;
            $totalPages = 1;

            if ($celebritiesData) {
                $totalResults = $celebritiesData['total_results'] ?? 0;
                $totalPages = $celebritiesData['total_pages'] ?? 1;

                foreach ($celebritiesData['results'] ?? [] as $person) {
                    $celebrities[] = [
                        'id' => $person['id'],
                        'name' => $person['name'],
                        'profile_path' => $person['profile_path'],
                        'profile_url' => $this->movieService->getPersonImageUrl($person['profile_path'], 'w185'),
                        'known_for_department' => $this->movieService->getFormattedDepartment($person['known_for_department'] ?? 'Acting'),
                        'popularity' => $person['popularity'] ?? 0,
                        'known_for' => array_slice($person['known_for'] ?? [], 0, 3), // Top 3 known movies
                    ];
                }
            }

            // Get random movie wallpaper
            $randomWallpaper = $this->getRandomMovieWallpapers();

            return view('celebrities.index', [
                'celebrities' => $celebrities,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalResults' => $totalResults,
                'currentSort' => $sort,
                'searchQuery' => $search,
                'randomWallpaper' => $randomWallpaper,
                'error' => null
            ]);

        } catch (\Exception $e) {
            Log::error('CelebrityController@index error: ' . $e->getMessage());

            return view('celebrities.index', [
                'celebrities' => [],
                'currentPage' => 1,
                'totalPages' => 1,
                'totalResults' => 0,
                'currentSort' => 'popularity',
                'searchQuery' => '',
                'randomWallpaper' => $this->getFallbackWallpaper(),
                'error' => 'Unable to load celebrities at the moment.'
            ]);
        }
    }

    /**
     * Show individual celebrity profile
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $celebrityDetails = $this->movieService->getPersonDetails($id);
            $movieCredits = $this->movieService->getPersonMovieCredits($id);

            if (!$celebrityDetails) {
                abort(404, 'Celebrity not found');
            }

            // Process celebrity data
            $celebrity = [
                'id' => $celebrityDetails['id'],
                'name' => $celebrityDetails['name'],
                'biography' => $celebrityDetails['biography'] ?? '',
                'birthday' => $celebrityDetails['birthday'] ?? null,
                'place_of_birth' => $celebrityDetails['place_of_birth'] ?? '',
                'profile_path' => $celebrityDetails['profile_path'],
                'profile_url' => $this->movieService->getPersonImageUrl($celebrityDetails['profile_path'], 'w300'),
                'known_for_department' => $this->movieService->getFormattedDepartment($celebrityDetails['known_for_department'] ?? 'Acting'),
                'popularity' => $celebrityDetails['popularity'] ?? 0,
                'homepage' => $celebrityDetails['homepage'] ?? null,
            ];

            // Process movie credits
            $movies = [];
            if ($movieCredits && isset($movieCredits['cast'])) {
                foreach (array_slice($movieCredits['cast'], 0, 20) as $movie) {
                    if (!empty($movie['poster_path'])) {
                        $movies[] = [
                            'id' => $movie['id'],
                            'title' => $movie['title'],
                            'character' => $movie['character'] ?? '',
                            'release_date' => $movie['release_date'] ?? '',
                            'poster_path' => $movie['poster_path'],
                            'poster_url' => $this->movieService->getImageUrl($movie['poster_path'], 'w300'),
                            'vote_average' => $movie['vote_average'] ?? 0,
                        ];
                    }
                }
            }

            $randomWallpaper = $this->getRandomMovieWallpapers();

            return view('celebrities.show', [
                'celebrity' => $celebrity,
                'movies' => $movies,
                'randomWallpaper' => $randomWallpaper,
                'error' => null
            ]);

        } catch (\Exception $e) {
            Log::error('CelebrityController@show error: ' . $e->getMessage());
            abort(404, 'Celebrity not found');
        }
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
}