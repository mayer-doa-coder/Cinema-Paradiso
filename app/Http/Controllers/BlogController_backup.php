<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\MovieService;                'randomWallpaper' => [$this->getFallbackWallpaper()],
                'error' => null
            ]);
            
        } catch (\Exception $e) {
            Log::error('BlogController@search error: ' . $e->getMessage());
            
            return redirect()->route('blog')->with('error', 'Search functionality is temporarily unavailable.');
        }Services\NewsService;

class BlogController extends Controller
{
    protected $movieService;
    protected $newsService;

    public function __construct(MovieService $movieService, NewsService $newsService)
    {
        $this->movieService = $movieService;
        $this->newsService = $newsService;
    }

    /**
     * Display the blog grid page with movie news
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $page = $request->get('page', 1);
            $limit = 12; // Articles per page

            // Get movie news from multiple sources
            $newsData = $this->newsService->getMovieNews($page, $limit);
            
            // Get Reddit discussions for additional content
            $discussions = $this->newsService->getRedditMovieDiscussions(6);

            // Get random movie wallpaper
            $randomWallpaper = $this->getRandomMovieWallpapers();

            return view('bloggrid', [
                'articles' => $newsData['articles'],
                'discussions' => $discussions,
                'pagination' => [
                    'current_page' => $newsData['current_page'],
                    'last_page' => $newsData['last_page'],
                    'per_page' => $newsData['per_page'],
                    'total' => $newsData['total']
                ],
                'randomWallpaper' => $randomWallpaper,
                'error' => null
            ]);

        } catch (\Exception $e) {
            Log::error('BlogController@index error: ' . $e->getMessage());

            return view('bloggrid', [
                'articles' => collect(),
                'discussions' => collect(),
                'pagination' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 12,
                    'total' => 0
                ],
                'randomWallpaper' => [$this->getFallbackWallpaper()],
                'error' => 'Unable to load news content at the moment.'
            ]);
        }
    }

    /**
     * Display the blog detail page
     *
     * @return \Illuminate\View\View
     */
    public function detail(Request $request)
    {
        try {
            $url = $request->get('url');
            $title = $request->get('title', 'Movie News Article');
            
            // Get related articles
            $relatedNews = $this->newsService->getMovieNews(1, 6);
            
            // Get random movie wallpaper
            $randomWallpaper = $this->getRandomMovieWallpapers();

            return view('blogdetail', [
                'articleUrl' => $url,
                'articleTitle' => $title,
                'relatedArticles' => $relatedNews['articles'],
                'randomWallpaper' => $randomWallpaper,
                'error' => null
            ]);

        } catch (\Exception $e) {
            Log::error('BlogController@detail error: ' . $e->getMessage());

            return view('blogdetail', [
                'articleUrl' => null,
                'articleTitle' => 'Article Not Found',
                'relatedArticles' => collect(),
                'randomWallpaper' => $this->getFallbackWallpaper(),
                'error' => 'Unable to load article content at the moment.'
            ]);
        }
    }

    /**
     * Search movie news articles
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');
            $page = $request->get('page', 1);
            
            if (empty($query)) {
                return redirect()->route('blog')->with('error', 'Please enter a search term.');
            }
            
            // Get all articles and filter by search term
            $newsData = $this->newsService->getMovieNews($page, 50); // Get more for filtering
            
            $filteredArticles = $newsData['articles']->filter(function ($article) use ($query) {
                return stripos($article['title'], $query) !== false || 
                       stripos($article['description'], $query) !== false;
            });
            
            $randomWallpaper = $this->getRandomMovieWallpapers();
            
            return view('bloggrid', [
                'articles' => $filteredArticles->take(12),
                'discussions' => collect(),
                'searchQuery' => $query,
                'pagination' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 12,
                    'total' => $filteredArticles->count()
                ],
                'randomWallpaper' => $randomWallpaper,
                'error' => null
            ]);
            
        } catch (\Exception $e) {
            Log::error('BlogController@search error: ' . $e->getMessage());
            
            return redirect()->route('blog')->with('error', 'Search functionality is temporarily unavailable.');
        }
    }

    /**
     * Clear news cache (admin function)
     */
    public function clearCache()
    {
        try {
            $this->newsService->clearCache();
            return response()->json(['success' => true, 'message' => 'News cache cleared successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to clear cache']);
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
            return !empty($uniqueWallpapers) ? $uniqueWallpapers : [$this->getFallbackWallpaper()];

        } catch (\Exception $e) {
            Log::error('Error getting random wallpapers: ' . $e->getMessage());
            return [$this->getFallbackWallpaper()];
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