<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserMovie;
use App\Models\UserMovieLike;
use App\Models\UserWatchlist;
use App\Models\UserMovieReview;

class UserMovieController extends Controller
{
    /**
     * Add movie to user's collection
     */
    public function addMovie(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|integer',
            'movie_title' => 'required|string',
            'movie_poster' => 'nullable|string',
            'rating' => 'required|numeric|min:0|max:10',
            'release_year' => 'nullable|integer',
        ]);

        try {
            $userMovie = UserMovie::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'movie_id' => $request->movie_id,
                ],
                [
                    'movie_title' => $request->movie_title,
                    'movie_poster' => $request->movie_poster,
                    'rating' => $request->rating,
                    'release_year' => $request->release_year,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Movie added to your collection successfully!',
                'data' => $userMovie
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add movie: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle like for a movie
     */
    public function toggleLike(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|integer',
        ]);

        try {
            $like = UserMovieLike::where([
                'user_id' => Auth::id(),
                'movie_id' => $request->movie_id,
            ])->first();

            if ($like) {
                $like->delete();
                return response()->json([
                    'success' => true,
                    'liked' => false,
                    'message' => 'Movie removed from favorites'
                ]);
            } else {
                UserMovieLike::create([
                    'user_id' => Auth::id(),
                    'movie_id' => $request->movie_id,
                ]);
                return response()->json([
                    'success' => true,
                    'liked' => true,
                    'message' => 'Movie added to favorites'
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
     * Toggle watchlist for a movie
     */
    public function toggleWatchlist(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|integer',
            'movie_title' => 'required|string',
            'movie_poster' => 'nullable|string',
            'release_year' => 'nullable|integer',
        ]);

        try {
            $watchlist = UserWatchlist::where([
                'user_id' => Auth::id(),
                'movie_id' => $request->movie_id,
            ])->first();

            if ($watchlist) {
                $watchlist->delete();
                return response()->json([
                    'success' => true,
                    'in_watchlist' => false,
                    'message' => 'Movie removed from watchlist'
                ]);
            } else {
                UserWatchlist::create([
                    'user_id' => Auth::id(),
                    'movie_id' => $request->movie_id,
                    'movie_title' => $request->movie_title,
                    'movie_poster' => $request->movie_poster,
                    'release_year' => $request->release_year,
                ]);
                return response()->json([
                    'success' => true,
                    'in_watchlist' => true,
                    'message' => 'Movie added to watchlist'
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
     * Submit movie review
     */
    public function submitReview(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|integer',
            'movie_title' => 'required|string',
            'movie_poster' => 'nullable|string',
            'rating' => 'required|numeric|min:0|max:10',
            'watched_before' => 'required|boolean',
            'review' => 'required|string|min:10',
            'release_year' => 'nullable|integer',
        ]);

        try {
            // Add movie to user's collection automatically
            UserMovie::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'movie_id' => $request->movie_id,
                ],
                [
                    'movie_title' => $request->movie_title,
                    'movie_poster' => $request->movie_poster,
                    'rating' => $request->rating,
                    'release_year' => $request->release_year,
                ]
            );

            // Create the review
            $movieReview = UserMovieReview::create([
                'user_id' => Auth::id(),
                'movie_id' => $request->movie_id,
                'movie_title' => $request->movie_title,
                'movie_poster' => $request->movie_poster,
                'rating' => $request->rating,
                'watched_before' => $request->watched_before,
                'review' => $request->review,
                'release_year' => $request->release_year,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review submitted and movie added to your collection!',
                'data' => $movieReview
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit review: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's movie status (liked, in watchlist, etc.)
     */
    public function getMovieStatus(Request $request, $movieId)
    {
        try {
            $userId = Auth::id();
            
            $status = [
                'in_collection' => UserMovie::where(['user_id' => $userId, 'movie_id' => $movieId])->exists(),
                'is_liked' => UserMovieLike::where(['user_id' => $userId, 'movie_id' => $movieId])->exists(),
                'in_watchlist' => UserWatchlist::where(['user_id' => $userId, 'movie_id' => $movieId])->exists(),
                'has_review' => UserMovieReview::where(['user_id' => $userId, 'movie_id' => $movieId])->exists(),
            ];

            return response()->json([
                'success' => true,
                'status' => $status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get movie status: ' . $e->getMessage()
            ], 500);
        }
    }
}
