<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\TVShowService;

class TVShowController extends Controller
{
    protected $tvShowService;

    public function __construct(TVShowService $tvShowService)
    {
        $this->tvShowService = $tvShowService;
    }

    /**
     * Display the TV shows index page
     */
    public function index(Request $request)
    {
        try {
            $page = $request->get('page', 1);
            $category = $request->get('category', 'popular');

            // Get TV shows based on category
            switch ($category) {
                case 'top-rated':
                    $tvShowsData = $this->tvShowService->getTopRatedTVShows($page);
                    $title = 'Top Rated TV Shows';
                    break;
                case 'trending':
                    $tvShowsData = $this->tvShowService->getTrendingTVShows($page);
                    $title = 'Trending TV Shows';
                    break;
                case 'airing-today':
                    $tvShowsData = $this->tvShowService->getAiringTodayTVShows($page);
                    $title = 'Airing Today';
                    break;
                case 'on-air':
                    $tvShowsData = $this->tvShowService->getOnTheAirTVShows($page);
                    $title = 'Currently On Air';
                    break;
                default:
                    $tvShowsData = $this->tvShowService->getPopularTVShows($page);
                    $title = 'Popular TV Shows';
                    break;
            }

            // Get all categories for navigation
            $popularShows = $this->tvShowService->getPopularTVShows(1);
            $topRatedShows = $this->tvShowService->getTopRatedTVShows(1);
            $trendingShows = $this->tvShowService->getTrendingTVShows(1);
            $onAirShows = $this->tvShowService->getOnTheAirTVShows(1);
            $airingTodayShows = $this->tvShowService->getAiringTodayTVShows(1);

            // Get genres
            $genres = $this->tvShowService->getTVGenres();

            // Get random wallpaper from trending shows
            $randomWallpaper = $this->getRandomTVShowWallpaper();

            return view('tv.index', [
                'tvShows' => $this->tvShowService->prepareTVShowsData($tvShowsData),
                'currentCategory' => $category,
                'title' => $title,
                'pagination' => $tvShowsData,
                'popular' => $this->tvShowService->prepareTVShowsData($popularShows),
                'topRated' => $this->tvShowService->prepareTVShowsData($topRatedShows),
                'trending' => $this->tvShowService->prepareTVShowsData($trendingShows),
                'onAir' => $this->tvShowService->prepareTVShowsData($onAirShows),
                'airingToday' => $this->tvShowService->prepareTVShowsData($airingTodayShows),
                'genres' => $genres['genres'] ?? [],
                'randomWallpaper' => $randomWallpaper,
                'error' => null
            ]);

        } catch (\Exception $e) {
            Log::error('TVShowController@index error: ' . $e->getMessage());

            return view('tv.index', [
                'tvShows' => [],
                'currentCategory' => $category,
                'title' => 'TV Shows',
                'pagination' => null,
                'popular' => [],
                'topRated' => [],
                'trending' => [],
                'onAir' => [],
                'airingToday' => [],
                'genres' => [],
                'randomWallpaper' => $this->getFallbackWallpaper(),
                'error' => 'Unable to load TV shows at the moment. Please try again later.'
            ]);
        }
    }

    /**
     * Display TV show details
     */
    public function show($id)
    {
        try {
            $tvShow = $this->tvShowService->getTVShowDetails($id);
            
            if (!$tvShow) {
                abort(404, 'TV Show not found');
            }

            // Get videos (trailers, teasers, etc.)
            $videos = $this->tvShowService->getTVShowVideos($id);
            
            // Get credits (cast and crew)
            $credits = $this->tvShowService->getTVShowCredits($id);

            // Format the TV show data
            $formattedTVShow = $this->tvShowService->formatTVShowData($tvShow);
            
            // Add additional details
            $formattedTVShow['seasons'] = $tvShow['seasons'] ?? [];
            $formattedTVShow['number_of_seasons'] = $tvShow['number_of_seasons'] ?? 0;
            $formattedTVShow['number_of_episodes'] = $tvShow['number_of_episodes'] ?? 0;
            $formattedTVShow['episode_run_time'] = $tvShow['episode_run_time'] ?? [];
            $formattedTVShow['status'] = $tvShow['status'] ?? '';
            $formattedTVShow['networks'] = $tvShow['networks'] ?? [];
            $formattedTVShow['created_by'] = $tvShow['created_by'] ?? [];
            $formattedTVShow['last_air_date'] = $tvShow['last_air_date'] ?? '';

            // Get random wallpaper
            $randomWallpaper = $this->getRandomTVShowWallpaper();

            return view('tv.show', [
                'tvShow' => $formattedTVShow,
                'videos' => $videos['results'] ?? [],
                'cast' => $credits['cast'] ?? [],
                'crew' => $credits['crew'] ?? [],
                'similar' => $this->tvShowService->prepareTVShowsData(['results' => $tvShow['similar']['results'] ?? []]),
                'recommendations' => $this->tvShowService->prepareTVShowsData(['results' => $tvShow['recommendations']['results'] ?? []]),
                'randomWallpaper' => $randomWallpaper,
                'error' => null
            ]);

        } catch (\Exception $e) {
            Log::error('TVShowController@show error: ' . $e->getMessage());
            
            return view('tv.show', [
                'tvShow' => null,
                'videos' => [],
                'cast' => [],
                'crew' => [],
                'similar' => [],
                'recommendations' => [],
                'randomWallpaper' => $this->getFallbackWallpaper(),
                'error' => 'Unable to load TV show details. Please try again later.'
            ]);
        }
    }

    /**
     * Search TV shows
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');
            $page = $request->get('page', 1);

            if (empty($query)) {
                return redirect()->route('tv.index');
            }

            $searchResults = $this->tvShowService->searchTVShows($query, $page);
            $randomWallpaper = $this->getRandomTVShowWallpaper();

            return view('tv.search', [
                'tvShows' => $this->tvShowService->prepareTVShowsData($searchResults),
                'query' => $query,
                'pagination' => $searchResults,
                'randomWallpaper' => $randomWallpaper,
                'error' => null
            ]);

        } catch (\Exception $e) {
            Log::error('TVShowController@search error: ' . $e->getMessage());

            return view('tv.search', [
                'tvShows' => [],
                'query' => $request->get('q', ''),
                'pagination' => null,
                'randomWallpaper' => $this->getFallbackWallpaper(),
                'error' => 'Unable to search TV shows at the moment. Please try again later.'
            ]);
        }
    }

    /**
     * Get TV shows by genre
     */
    public function byGenre($genreId, Request $request)
    {
        try {
            $page = $request->get('page', 1);
            $tvShowsData = $this->tvShowService->getTVShowsByGenre($genreId, $page);
            $genres = $this->tvShowService->getTVGenres();
            
            // Find genre name
            $genreName = 'TV Shows';
            if ($genres && isset($genres['genres'])) {
                foreach ($genres['genres'] as $genre) {
                    if ($genre['id'] == $genreId) {
                        $genreName = $genre['name'] . ' TV Shows';
                        break;
                    }
                }
            }

            $randomWallpaper = $this->getRandomTVShowWallpaper();

            return view('tv.genre', [
                'tvShows' => $this->tvShowService->prepareTVShowsData($tvShowsData),
                'genreId' => $genreId,
                'genreName' => $genreName,
                'pagination' => $tvShowsData,
                'genres' => $genres['genres'] ?? [],
                'randomWallpaper' => $randomWallpaper,
                'error' => null
            ]);

        } catch (\Exception $e) {
            Log::error('TVShowController@byGenre error: ' . $e->getMessage());

            return view('tv.genre', [
                'tvShows' => [],
                'genreId' => $genreId,
                'genreName' => 'TV Shows',
                'pagination' => null,
                'genres' => [],
                'randomWallpaper' => $this->getFallbackWallpaper(),
                'error' => 'Unable to load TV shows by genre at the moment. Please try again later.'
            ]);
        }
    }

    /**
     * Get TV show season details
     */
    public function season($tvId, $seasonNumber)
    {
        try {
            $tvShow = $this->tvShowService->getTVShowDetails($tvId);
            $season = $this->tvShowService->getTVSeasonDetails($tvId, $seasonNumber);
            
            if (!$tvShow || !$season) {
                abort(404, 'Season not found');
            }

            $randomWallpaper = $this->getRandomTVShowWallpaper();

            return view('tv.season', [
                'tvShow' => $this->tvShowService->formatTVShowData($tvShow),
                'season' => $season,
                'seasonNumber' => $seasonNumber,
                'randomWallpaper' => $randomWallpaper,
                'error' => null
            ]);

        } catch (\Exception $e) {
            Log::error('TVShowController@season error: ' . $e->getMessage());
            
            return view('tv.season', [
                'tvShow' => null,
                'season' => null,
                'seasonNumber' => $seasonNumber,
                'randomWallpaper' => $this->getFallbackWallpaper(),
                'error' => 'Unable to load season details. Please try again later.'
            ]);
        }
    }

    /**
     * Get random TV show wallpaper
     */
    private function getRandomTVShowWallpaper()
    {
        try {
            $categories = [
                $this->tvShowService->getPopularTVShows(1),
                $this->tvShowService->getTrendingTVShows(1),
                $this->tvShowService->getTopRatedTVShows(1),
            ];

            $allWallpapers = [];

            foreach ($categories as $category) {
                if (isset($category['results'])) {
                    foreach ($category['results'] as $tvShow) {
                        if (!empty($tvShow['backdrop_path'])) {
                            $allWallpapers[] = [
                                'id' => $tvShow['id'],
                                'title' => $tvShow['name'],
                                'backdrop_path' => $tvShow['backdrop_path'],
                                'backdrop_url' => $this->tvShowService->getImageUrl($tvShow['backdrop_path'], 'w780'),
                                'overview' => substr($tvShow['overview'] ?? '', 0, 100) . '...'
                            ];
                        }
                    }
                }
            }

            // Remove duplicates
            $uniqueWallpapers = [];
            $seenIds = [];
            foreach ($allWallpapers as $wallpaper) {
                if (!in_array($wallpaper['id'], $seenIds)) {
                    $uniqueWallpapers[] = $wallpaper;
                    $seenIds[] = $wallpaper['id'];
                }
            }

            shuffle($uniqueWallpapers);
            return !empty($uniqueWallpapers) ? $uniqueWallpapers : [$this->getFallbackWallpaper()];

        } catch (\Exception $e) {
            Log::error('Error getting random TV wallpapers: ' . $e->getMessage());
            return [$this->getFallbackWallpaper()];
        }
    }

    /**
     * Get fallback wallpaper
     */
    private function getFallbackWallpaper()
    {
        return [
            'id' => null,
            'title' => 'Cinema Paradiso TV',
            'backdrop_path' => null,
            'backdrop_url' => asset('images/cinema_paradiso.png'),
            'overview' => 'Discover amazing TV shows and series.'
        ];
    }
}