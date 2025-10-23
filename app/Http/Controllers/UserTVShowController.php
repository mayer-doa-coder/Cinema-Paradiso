<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserMovie;
use App\Models\UserMovieLike;
use App\Models\UserWatchlist;
use App\Models\UserMovieReview;
use App\Models\UserFavoriteMovie;
use App\Models\UserActivity;
use App\Models\User;
use App\Services\TVShowService;

class UserTVShowController extends Controller
{
    /**
     * Add TV show to user's collection
     */
    public function addShow(Request $request)
    {
        $request->validate([
            'show_id' => 'required|integer',
            'show_title' => 'required|string',
            'show_poster' => 'nullable|string',
            'rating' => 'required|numeric|min:0|max:10',
            'first_air_year' => 'nullable|integer',
        ]);

        try {
            $user = Auth::user();
            $isNewShow = !UserMovie::where(['user_id' => $user->id, 'movie_id' => $request->show_id])->exists();
            
            // Check if poster is missing or invalid and fetch from TMDB
            $posterPath = $request->show_poster;
            if (empty($posterPath) || $posterPath === 'null' || $posterPath === 'undefined') {
                try {
                    $tvService = app(TVShowService::class);
                    $showDetails = $tvService->getTVShowDetails($request->show_id);
                    $posterPath = $showDetails['poster_path'] ?? null;
                } catch (\Exception $e) {
                    \Log::error("Failed to fetch poster for TV show {$request->show_id}: " . $e->getMessage());
                }
            }
            
            $userShow = UserMovie::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'movie_id' => $request->show_id,
                ],
                [
                    'movie_title' => $request->show_title,
                    'movie_poster' => $posterPath,
                    'rating' => $request->rating,
                    'release_year' => $request->first_air_year,
                ]
            );

            // Update ALL UserMovie records with this show_id to have the correct poster
            UserMovie::where('movie_id', $request->show_id)->update([
                'movie_poster' => $posterPath
            ]);

            // If it's a new show, add it to favorites and track activity
            if ($isNewShow) {
                // Add to UserFavoriteMovie
                UserFavoriteMovie::create([
                    'user_id' => $user->id,
                    'movie_id' => $request->show_id,
                    'movie_title' => $request->show_title,
                    'movie_poster' => $posterPath,
                    'user_rating' => $request->rating,
                    'watched_at' => now(),
                ]);

                // Track activity
                UserActivity::create([
                    'user_id' => $user->id,
                    'activity_type' => 'movie_watched',
                    'activity_data' => [
                        'movie_id' => $request->show_id,
                        'movie_title' => $request->show_title,
                        'rating' => $request->rating,
                        'type' => 'tv'
                    ],
                    'points' => UserActivity::getActivityPoints('movie_watched'),
                ]);

                // Update user statistics
                $user->increment('total_movies_watched');
                $user->updatePopularityScore();
            }

            return response()->json([
                'success' => true,
                'message' => 'TV show added to your collection successfully!',
                'data' => $userShow
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add TV show: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle like for a TV show
     */
    public function toggleLike(Request $request)
    {
        $request->validate([
            'show_id' => 'required|integer',
            'show_title' => 'nullable|string',
            'show_poster' => 'nullable|string',
        ]);

        try {
            $user = Auth::user();
            $like = UserMovieLike::where([
                'user_id' => $user->id,
                'movie_id' => $request->show_id,
            ])->first();

            if ($like) {
                $like->delete();
                
                // Remove from favorites if exists
                UserFavoriteMovie::where([
                    'user_id' => $user->id,
                    'movie_id' => $request->show_id,
                ])->delete();
                
                return response()->json([
                    'success' => true,
                    'liked' => false,
                    'message' => 'TV show removed from favorites'
                ]);
            } else {
                // Check if poster is missing or invalid and fetch from TMDB
                $posterPath = $request->show_poster;
                if (empty($posterPath) || $posterPath === 'null' || $posterPath === 'undefined') {
                    try {
                        $tvService = app(TVShowService::class);
                        $showDetails = $tvService->getTVShowDetails($request->show_id);
                        $posterPath = $showDetails['poster_path'] ?? null;
                    } catch (\Exception $e) {
                        \Log::error("Failed to fetch poster for TV show {$request->show_id}: " . $e->getMessage());
                    }
                }
                
                UserMovieLike::create([
                    'user_id' => $user->id,
                    'movie_id' => $request->show_id,
                ]);

                // Add to favorites
                UserFavoriteMovie::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'movie_id' => $request->show_id,
                    ],
                    [
                        'movie_title' => $request->show_title ?? 'Unknown Show',
                        'movie_poster' => $posterPath,
                        'watched_at' => now(),
                    ]
                );

                // Update ALL UserMovie records with this show_id to have the correct poster
                UserMovie::where('movie_id', $request->show_id)->update([
                    'movie_poster' => $posterPath
                ]);

                // Track activity
                UserActivity::create([
                    'user_id' => $user->id,
                    'activity_type' => 'favorite',
                    'activity_data' => [
                        'movie_id' => $request->show_id,
                        'movie_title' => $request->show_title ?? 'Unknown Show',
                        'type' => 'tv'
                    ],
                    'points' => UserActivity::getActivityPoints('favorite'),
                ]);

                $user->updatePopularityScore();

                return response()->json([
                    'success' => true,
                    'liked' => true,
                    'message' => 'TV show added to favorites'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle like: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle watchlist for a TV show
     */
    public function toggleWatchlist(Request $request)
    {
        $request->validate([
            'show_id' => 'required|integer',
            'show_name' => 'required|string',
            'show_poster' => 'nullable|string',
            'first_air_year' => 'nullable|integer',
        ]);

        try {
            $user = Auth::user();
            $watchlist = UserWatchlist::where([
                'user_id' => $user->id,
                'movie_id' => $request->show_id,
            ])->first();

            if ($watchlist) {
                $watchlist->delete();
                return response()->json([
                    'success' => true,
                    'in_watchlist' => false,
                    'message' => 'TV show removed from watchlist'
                ]);
            } else {
                // Check if poster is missing or invalid and fetch from TMDB
                $posterPath = $request->show_poster;
                if (empty($posterPath) || $posterPath === 'null' || $posterPath === 'undefined') {
                    try {
                        $tvService = app(TVShowService::class);
                        $showDetails = $tvService->getTVShowDetails($request->show_id);
                        $posterPath = $showDetails['poster_path'] ?? null;
                    } catch (\Exception $e) {
                        \Log::error("Failed to fetch poster for TV show {$request->show_id}: " . $e->getMessage());
                    }
                }
                
                UserWatchlist::create([
                    'user_id' => $user->id,
                    'movie_id' => $request->show_id,
                    'movie_title' => $request->show_name,
                    'movie_poster' => $posterPath,
                    'release_year' => $request->first_air_year,
                ]);

                // Track activity
                UserActivity::create([
                    'user_id' => $user->id,
                    'activity_type' => 'watchlist_add',
                    'activity_data' => [
                        'movie_id' => $request->show_id,
                        'movie_title' => $request->show_name,
                        'type' => 'tv'
                    ],
                    'points' => 1,
                ]);

                $user->updatePopularityScore();

                return response()->json([
                    'success' => true,
                    'in_watchlist' => true,
                    'message' => 'TV show added to watchlist'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle watchlist: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit TV show review
     */
    public function submitReview(Request $request)
    {
        $request->validate([
            'show_id' => 'required|integer',
            'show_title' => 'required|string',
            'show_poster' => 'nullable|string',
            'rating' => 'required|numeric|min:1|max:10',
            'review_text' => 'required|string|min:10',
            'watched_before' => 'nullable|boolean',
            'first_air_year' => 'nullable|integer',
        ]);

        try {
            $user = Auth::user();
            $isNewReview = !UserMovieReview::where(['user_id' => $user->id, 'movie_id' => $request->show_id])->exists();

            // Check if poster is missing or invalid and fetch from TMDB
            $posterPath = $request->show_poster;
            if (empty($posterPath) || $posterPath === 'null' || $posterPath === 'undefined') {
                try {
                    $tvService = app(TVShowService::class);
                    $showDetails = $tvService->getTVShowDetails($request->show_id);
                    $posterPath = $showDetails['poster_path'] ?? null;
                } catch (\Exception $e) {
                    \Log::error("Failed to fetch poster for TV show {$request->show_id}: " . $e->getMessage());
                }
            }

            // Add show to user's collection automatically
            UserMovie::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'movie_id' => $request->show_id,
                ],
                [
                    'movie_title' => $request->show_title,
                    'movie_poster' => $posterPath,
                    'rating' => $request->rating,
                    'release_year' => $request->first_air_year,
                ]
            );

            // Update ALL UserMovie records with this show_id to have the correct poster
            UserMovie::where('movie_id', $request->show_id)->update([
                'movie_poster' => $posterPath
            ]);

            // Add to favorites
            UserFavoriteMovie::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'movie_id' => $request->show_id,
                ],
                [
                    'movie_title' => $request->show_title,
                    'movie_poster' => $posterPath,
                    'user_rating' => $request->rating,
                    'user_review' => $request->review_text,
                    'watched_at' => now(),
                ]
            );

            // Create or update the review
            $review = UserMovieReview::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'movie_id' => $request->show_id,
                ],
                [
                    'movie_title' => $request->show_title,
                    'movie_poster' => $posterPath,
                    'rating' => $request->rating,
                    'review' => $request->review_text,
                    'watched_before' => $request->watched_before ?? false,
                    'release_year' => $request->first_air_year,
                ]
            );

            // Track activity for new reviews only
            if ($isNewReview) {
                UserActivity::create([
                    'user_id' => $user->id,
                    'activity_type' => 'review',
                    'activity_data' => [
                        'movie_id' => $request->show_id,
                        'movie_title' => $request->show_title,
                        'rating' => $request->rating,
                        'review_snippet' => substr($request->review_text, 0, 100),
                        'type' => 'tv'
                    ],
                    'points' => UserActivity::getActivityPoints('review'),
                ]);

                // Update user statistics
                $user->increment('total_reviews');
                $user->increment('total_movies_watched');
            }

            $user->updatePopularityScore();

            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully!',
                'data' => $review
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit review: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get TV show status for current user
     */
    public function getShowStatus($showId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => true,
                'in_collection' => false,
                'liked' => false,
                'in_watchlist' => false,
                'user_rating' => 0
            ]);
        }

        $userId = Auth::id();

        $inCollection = UserMovie::where('user_id', $userId)
            ->where('movie_id', $showId)
            ->exists();

        $liked = UserMovieLike::where('user_id', $userId)
            ->where('movie_id', $showId)
            ->exists();

        $inWatchlist = UserWatchlist::where('user_id', $userId)
            ->where('movie_id', $showId)
            ->exists();

        $userRating = UserMovie::where('user_id', $userId)
            ->where('movie_id', $showId)
            ->value('rating') ?? 0;

        return response()->json([
            'success' => true,
            'in_collection' => $inCollection,
            'liked' => $liked,
            'in_watchlist' => $inWatchlist,
            'user_rating' => $userRating
        ]);
    }
}
