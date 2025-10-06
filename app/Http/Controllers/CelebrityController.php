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
            $letter = $request->get('letter'); // For alphabetical pagination
            $subPage = $request->get('subpage', 1); // For sub-pagination within a letter

            // Get celebrities data
            if ($search) {
                $celebritiesData = $this->movieService->searchPeople($search, $page);
                $celebrities = $this->processCelebritiesData($celebritiesData);
                
                return view('celebrities.index', [
                    'celebrities' => $celebrities,
                    'currentPage' => $page,
                    'totalPages' => $celebritiesData['total_pages'] ?? 1,
                    'totalResults' => $celebritiesData['total_results'] ?? 0,
                    'currentSort' => $sort,
                    'searchQuery' => $search,
                    'randomWallpaper' => $this->getRandomMovieWallpapers(),
                    'error' => null,
                    'isAlphabetical' => false
                ]);
            }

            if ($sort === 'name') {
                return $this->handleAlphabeticalPagination($letter, $subPage, $sort);
            } else {
                // Standard popularity-based pagination
                $celebritiesData = $this->movieService->getPopularPeople($page);
                $celebrities = $this->processCelebritiesData($celebritiesData);

                return view('celebrities.index', [
                    'celebrities' => $celebrities,
                    'currentPage' => $page,
                    'totalPages' => $celebritiesData['total_pages'] ?? 1,
                    'totalResults' => $celebritiesData['total_results'] ?? 0,
                    'currentSort' => $sort,
                    'searchQuery' => $search,
                    'randomWallpaper' => $this->getRandomMovieWallpapers(),
                    'error' => null,
                    'isAlphabetical' => false
                ]);
            }

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
                'error' => 'Unable to load celebrities at the moment.',
                'isAlphabetical' => false
            ]);
        }
    }

    /**
     * Handle alphabetical pagination system
     */
    private function handleAlphabeticalPagination($letter, $subPage, $sort)
    {
        // If no letter specified, default to 'A'
        if (!$letter) {
            $letter = 'A';
            $subPage = 1;
        }

        // Get all celebrities for the specified letter
        $allCelebritiesForLetter = $this->getCelebritiesByLetter($letter);
        
        // Pagination settings - smaller pages for faster loading
        $itemsPerPage = 16; // Reduced from 20 to 16 (4x4 grid)
        $totalCelebritiesForLetter = count($allCelebritiesForLetter);
        $totalSubPages = max(1, ceil($totalCelebritiesForLetter / $itemsPerPage));
        
        // Get celebrities for current sub-page
        $offset = ($subPage - 1) * $itemsPerPage;
        $celebrities = array_slice($allCelebritiesForLetter, $offset, $itemsPerPage);

        // Get available letters (letters that have celebrities)
        $availableLetters = $this->getAvailableLetters();

        return view('celebrities.index', [
            'celebrities' => $celebrities,
            'currentPage' => $subPage,
            'totalPages' => $totalSubPages,
            'totalResults' => $totalCelebritiesForLetter,
            'currentSort' => $sort,
            'searchQuery' => '',
            'randomWallpaper' => $this->getRandomMovieWallpapers(),
            'error' => null,
            'isAlphabetical' => true,
            'currentLetter' => $letter,
            'currentSubPage' => $subPage,
            'availableLetters' => $availableLetters,
            'totalSubPages' => $totalSubPages
        ]);
    }

    /**
     * Get celebrities that start with a specific letter
     */
    private function getCelebritiesByLetter($letter)
    {
        // Use the optimized service method for better performance
        return $this->processCelebritiesFromRawData(
            $this->movieService->getCelebritiesByLetterFast($letter, 60)
        );
    }

    /**
     * Get list of available letters that have celebrities
     */
    private function getAvailableLetters()
    {
        // Use the optimized service method for better performance
        return $this->movieService->getAvailableLettersFast();
    }

    /**
     * Process celebrities data from raw TMDB data
     */
    private function processCelebritiesFromRawData($rawCelebrities)
    {
        $celebrities = [];
        
        foreach ($rawCelebrities as $person) {
            $celebrities[] = [
                'id' => $person['id'],
                'name' => $person['name'],
                'profile_path' => $person['profile_path'],
                'profile_url' => $this->movieService->getPersonImageUrl($person['profile_path'], 'w185'),
                'known_for_department' => $this->movieService->getFormattedDepartment($person['known_for_department'] ?? 'Acting'),
                'popularity' => $person['popularity'] ?? 0,
                'known_for' => array_slice($person['known_for'] ?? [], 0, 3),
            ];
        }
        
        return $celebrities;
    }



    /**
     * Process celebrities data into standardized format
     */
    private function processCelebritiesData($celebritiesData)
    {
        $celebrities = [];
        
        if ($celebritiesData && isset($celebritiesData['results'])) {
            foreach ($celebritiesData['results'] as $person) {
                $celebrities[] = [
                    'id' => $person['id'],
                    'name' => $person['name'],
                    'profile_path' => $person['profile_path'],
                    'profile_url' => $this->movieService->getPersonImageUrl($person['profile_path'], 'w185'),
                    'known_for_department' => $this->movieService->getFormattedDepartment($person['known_for_department'] ?? 'Acting'),
                    'popularity' => $person['popularity'] ?? 0,
                    'known_for' => array_slice($person['known_for'] ?? [], 0, 3),
                ];
            }
        }
        
        return $celebrities;
    }

    /**
     * Preload comprehensive celebrity data for better performance
     * This method can be called to populate cache with comprehensive data
     */
    public function preloadData()
    {
        try {
            Log::info('Starting comprehensive celebrity data preload...');
            
            // Get comprehensive people data and group by letters
            $peopleByLetter = $this->movieService->getPeopleByFirstLetter(200); // Fetch up to 200 pages
            
            $totalPeople = 0;
            foreach ($peopleByLetter as $letter => $people) {
                $totalPeople += count($people);
                Log::info("Letter {$letter}: " . count($people) . " celebrities");
            }
            
            Log::info("Total celebrities preloaded: {$totalPeople}");
            
            return response()->json([
                'success' => true,
                'message' => "Preloaded {$totalPeople} celebrities across all letters",
                'data' => array_map('count', $peopleByLetter)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error preloading celebrity data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error preloading data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics about available data
     */
    public function getDataStats()
    {
        try {
            $stats = [];
            
            // Check celebrity data across first few pages
            for ($page = 1; $page <= 10; $page++) {
                $celebritiesData = $this->movieService->getPopularPeople($page);
                if ($celebritiesData) {
                    $stats['total_celebrity_pages'] = $celebritiesData['total_pages'] ?? 0;
                    $stats['total_celebrities'] = $celebritiesData['total_results'] ?? 0;
                    break;
                }
            }
            
            // Check movie data
            $movieCategories = ['popular', 'top-rated', 'upcoming', 'trending'];
            $stats['movies'] = [];
            
            foreach ($movieCategories as $category) {
                $movieData = null;
                switch ($category) {
                    case 'top-rated':
                        $movieData = $this->movieService->getTopRatedMovies(1);
                        break;
                    case 'upcoming':
                        $movieData = $this->movieService->getUpcomingMovies(1);
                        break;
                    case 'trending':
                        $movieData = $this->movieService->getTrendingMovies(1);
                        break;
                    default:
                        $movieData = $this->movieService->getPopularMovies(1);
                        break;
                }
                
                if ($movieData) {
                    $stats['movies'][$category] = [
                        'total_pages' => $movieData['total_pages'] ?? 0,
                        'total_results' => $movieData['total_results'] ?? 0
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting data stats: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error getting stats: ' . $e->getMessage()
            ], 500);
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