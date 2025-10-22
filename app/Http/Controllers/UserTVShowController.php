<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserMovie;
use App\Models\UserMovieLike;
use App\Models\UserWatchlist;
use App\Models\UserMovieReview;

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
            $userShow = UserMovie::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'movie_id' => $request->show_id,
                ],
                [
                    'movie_title' => $request->show_title,
                    'movie_poster' => $request->show_poster,
                    'rating' => $request->rating,
                    'release_year' => $request->first_air_year,
                ]
            );

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
        ]);

        try {
            $like = UserMovieLike::where([
                'user_id' => Auth::id(),
                'movie_id' => $request->show_id,
            ])->first();

            if ($like) {
                $like->delete();
                return response()->json([
                    'success' => true,
                    'liked' => false,
                    'message' => 'TV show removed from favorites'
                ]);
            } else {
                UserMovieLike::create([
                    'user_id' => Auth::id(),
                    'movie_id' => $request->show_id,
                ]);
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
            $watchlist = UserWatchlist::where([
                'user_id' => Auth::id(),
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
                UserWatchlist::create([
                    'user_id' => Auth::id(),
                    'movie_id' => $request->show_id,
                    'movie_title' => $request->show_name,
                    'movie_poster' => $request->show_poster,
                    'release_year' => $request->first_air_year,
                    'media_type' => 'tv',
                ]);
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
            $review = UserMovieReview::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'movie_id' => $request->show_id,
                ],
                [
                    'movie_title' => $request->show_title,
                    'movie_poster' => $request->show_poster,
                    'rating' => $request->rating,
                    'review' => $request->review_text,
                    'watched_before' => $request->watched_before ?? false,
                    'release_year' => $request->first_air_year,
                ]
            );

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
