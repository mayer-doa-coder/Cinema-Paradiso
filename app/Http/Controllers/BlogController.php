<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\MovieService;

class BlogController extends Controller
{
    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    /**
     * Display the blog grid page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Get random movie wallpaper
            $randomWallpaper = $this->getRandomMovieWallpapers();

            return view('bloggrid', [
                'randomWallpaper' => $randomWallpaper,
                'error' => null
            ]);

        } catch (\Exception $e) {
            Log::error('BlogController@index error: ' . $e->getMessage());

            return view('bloggrid', [
                'randomWallpaper' => $this->getFallbackWallpaper(),
                'error' => 'Unable to load some content at the moment.'
            ]);
        }
    }

    /**
     * Display the blog detail page
     *
     * @return \Illuminate\View\View
     */
    public function detail()
    {
        try {
            // Get random movie wallpaper
            $randomWallpaper = $this->getRandomMovieWallpapers();

            return view('blogdetail', [
                'randomWallpaper' => $randomWallpaper,
                'error' => null
            ]);

        } catch (\Exception $e) {
            Log::error('BlogController@detail error: ' . $e->getMessage());

            return view('blogdetail', [
                'randomWallpaper' => $this->getFallbackWallpaper(),
                'error' => 'Unable to load some content at the moment.'
            ]);
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