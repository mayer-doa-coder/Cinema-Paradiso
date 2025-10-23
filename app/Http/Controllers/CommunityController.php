<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\MovieService;
use App\Models\User;
use App\Models\UserFavoriteMovie;
use App\Models\UserActivity;
use App\Models\UserFollower;
use App\Models\UserMovie;
use App\Models\UserMovieReview;

class CommunityController extends Controller
{
    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    /**
     * Display the community page with popular users
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $perPage = 20;
            $sortBy = $request->get('sort', 'popularity'); // popularity, recent, alphabetical
            
            // Get users based on sorting preference
            $usersQuery = User::where('is_public', true)->with(['favoriteMovies', 'followers', 'following']);
            
            switch ($sortBy) {
                case 'recent':
                    $usersQuery->orderByDesc('last_active')->orderByDesc('created_at');
                    break;
                case 'alphabetical':
                    $usersQuery->orderBy('name');
                    break;
                case 'popularity':
                default:
                    $usersQuery->orderByDesc('popularity_score')->orderByDesc('last_active');
                    break;
            }
            
            $users = $usersQuery->paginate($perPage);
            
            // Get some statistics
            $totalUsers = User::where('is_public', true)->count();
            $activeUsers = User::where('is_public', true)->active()->count();
            $totalMoviesWatched = UserFavoriteMovie::count();
            
            // Get random movie wallpaper
            $randomWallpaper = $this->getRandomMovieWallpapers();
            
            return view('community.index', [
                'users' => $users,
                'totalUsers' => $totalUsers,
                'activeUsers' => $activeUsers,
                'totalMoviesWatched' => $totalMoviesWatched,
                'currentSort' => $sortBy,
                'randomWallpaper' => $randomWallpaper,
                'error' => null
            ]);
            
        } catch (\Exception $e) {
            Log::error('CommunityController@index error: ' . $e->getMessage());
            
            return view('community.index', [
                'users' => collect(),
                'totalUsers' => 0,
                'activeUsers' => 0,
                'totalMoviesWatched' => 0,
                'currentSort' => 'popularity',
                'randomWallpaper' => $this->getFallbackWallpaper(),
                'error' => 'Unable to load community data at the moment.'
            ]);
        }
    }

    /**
     * Display a user's profile page
     *
     * @param string $username
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function profile($username)
    {
        try {
            $user = User::where('username', $username)
                       ->where('is_public', true)
                       ->with(['favoriteMovies', 'followers', 'following', 'activities'])
                       ->firstOrFail();
            
            // Get user's favorite movies with pagination
            $favoriteMovies = $user->favoriteMovies()->orderByDesc('created_at')->paginate(12, ['*'], 'favorites_page');
            
            // Get all user's movies (for Movies tab)
            $userMovies = UserMovie::where('user_id', $user->id)
                                   ->orderByDesc('created_at')
                                   ->paginate(12, ['*'], 'movies_page');
            
            // Get user's reviews (for Reviews tab)
            $userReviews = UserMovieReview::where('user_id', $user->id)
                                         ->orderByDesc('created_at')
                                         ->paginate(12, ['*'], 'reviews_page');
            
            // Get recent activities
            $recentActivities = $user->activities()
                                   ->orderByDesc('created_at')
                                   ->limit(10)
                                   ->get();
            
            // Get user statistics
            $stats = [
                'followers_count' => $user->followers()->count(),
                'following_count' => $user->following()->count(),
                'movies_watched' => $user->total_movies_watched,
                'reviews_count' => $user->total_reviews,
                'popularity_score' => $user->popularity_score,
                'member_since' => $user->created_at->format('M Y'),
                'last_active' => $user->last_active ? $user->last_active->diffForHumans() : 'Never'
            ];
            
            // Get random movie wallpaper
            $randomWallpaper = $this->getRandomMovieWallpapers();
            
            return view('community.profile', [
                'user' => $user,
                'favoriteMovies' => $favoriteMovies,
                'userMovies' => $userMovies,
                'userReviews' => $userReviews,
                'recentActivities' => $recentActivities,
                'stats' => $stats,
                'randomWallpaper' => $randomWallpaper,
                'error' => null
            ]);
            
        } catch (\Exception $e) {
            Log::error('CommunityController@profile error: ' . $e->getMessage());
            
            return redirect()->route('community')->with('error', 'User profile not found or is private.');
        }
    }

    /**
     * Search for users in the community
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('q');
            $limit = $request->get('limit', 10);
            
            if (empty($query)) {
                return response()->json(['users' => []]);
            }
            
            $users = User::where('is_public', true)
                        ->where(function($q) use ($query) {
                            $q->where('name', 'LIKE', "%{$query}%")
                              ->orWhere('username', 'LIKE', "%{$query}%");
                        })
                        ->orderByDesc('popularity_score')
                        ->limit($limit)
                        ->get(['id', 'name', 'username', 'avatar', 'popularity_score', 'location']);
            
            return response()->json([
                'users' => $users->map(function($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'avatar_url' => $user->avatar_url,
                        'popularity_score' => $user->popularity_score,
                        'location' => $user->location,
                        'profile_url' => route('community.profile', $user->username)
                    ];
                })
            ]);
            
        } catch (\Exception $e) {
            Log::error('CommunityController@search error: ' . $e->getMessage());
            return response()->json(['error' => 'Search failed'], 500);
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

    /**
     * Check follow status
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function followStatus($userId)
    {
        try {
            $currentUser = Auth::user();
            
            $isFollowing = UserFollower::where('follower_id', $currentUser->id)
                                      ->where('following_id', $userId)
                                      ->exists();
            
            $followsBack = UserFollower::where('follower_id', $userId)
                                      ->where('following_id', $currentUser->id)
                                      ->exists();
            
            return response()->json([
                'success' => true,
                'is_following' => $isFollowing,
                'follows_back' => $followsBack
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking follow status'
            ], 500);
        }
    }
}