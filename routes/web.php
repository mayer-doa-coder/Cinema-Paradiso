<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TVShowController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CelebrityController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserMovieController;
use App\Http\Controllers\UserTVShowController;

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('home.search');

// Universal search route for all sections
Route::get('/universal-search', [HomeController::class, 'search'])->name('universal.search');

// Movie routes
Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/search', [MovieController::class, 'search'])->name('movies.search');
Route::get('/movies/genre/{genreId}', [MovieController::class, 'byGenre'])->name('movies.genre');
Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movies.show');

// TV Show routes
Route::get('/tv', [TVShowController::class, 'index'])->name('tv.index');
Route::get('/tv/search', [TVShowController::class, 'search'])->name('tv.search');
Route::get('/tv/genre/{genreId}', [TVShowController::class, 'byGenre'])->name('tv.genre');
Route::get('/tv/{id}', [TVShowController::class, 'show'])->name('tv.show');
Route::get('/tv/{id}/season/{seasonNumber}', [TVShowController::class, 'season'])->name('tv.season');

// AJAX endpoints for TV show data
Route::get('/api/tv/{id}/credits', [TVShowController::class, 'getCredits'])->name('api.tv.credits');
Route::get('/api/tv/{id}/images', [TVShowController::class, 'getImages'])->name('api.tv.images');
Route::get('/api/tv/{id}/videos', [TVShowController::class, 'getVideos'])->name('api.tv.videos');

// AJAX endpoints for movie data
Route::get('/api/movies/{id}/credits', [MovieController::class, 'getCredits'])->name('api.movies.credits');
Route::get('/api/movies/{id}/images', [MovieController::class, 'getImages'])->name('api.movies.images');
Route::get('/api/movies/{id}/videos', [MovieController::class, 'getVideos'])->name('api.movies.videos');

// Legacy movie routes (redirect to new ones)
Route::get('/moviegrid', [MovieController::class, 'grid'])->name('moviegrid');
Route::get('/movielist', [MovieController::class, 'list'])->name('movielist');

// Celebrity routes
Route::get('/celebrities', [CelebrityController::class, 'index'])->name('celebrities');
Route::get('/celebrities/{id}', [CelebrityController::class, 'show'])->name('celebrities.show');

// Data management routes (for admin/development)
Route::get('/admin/preload-data', [CelebrityController::class, 'preloadData'])->name('admin.preload');
Route::get('/admin/data-stats', [CelebrityController::class, 'getDataStats'])->name('admin.stats');

Route::get('/celebritygrid01', [CelebrityController::class, 'index'])->name('celebritygrid01');

Route::get('/celebritygrid02', function () {
    return view('celebritygrid02');
})->name('celebritygrid02');

Route::get('/celebritylist', function () {
    return view('celebritylist');
})->name('celebritylist');

Route::get('/celebritysingle', function () {
    return view('celebritysingle');
})->name('celebritysingle');

// Community routes
Route::get('/community', [CommunityController::class, 'index'])->name('community');
Route::get('/community/search', [CommunityController::class, 'search'])->name('community.search');
Route::get('/community/{username}', [CommunityController::class, 'profile'])->name('community.profile');

// Blog/News routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search');
Route::get('/blog/clear-cache', [BlogController::class, 'clearCache'])->name('blog.clear-cache');

Route::get('/bloggrid', [BlogController::class, 'index'])->name('bloggrid');

Route::get('/blogdetail', [BlogController::class, 'detail'])->name('blogdetail');

// Help and Contact routes
Route::get('/help', function () {
    return view('help');
})->name('help');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

// User Profile routes (protected)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('user.profile.update');
    Route::post('/profile/password', [UserController::class, 'updatePassword'])->name('user.password.update');
    Route::post('/profile/favorites', [UserController::class, 'updateFavorites'])->name('user.favorites.update');
    Route::post('/profile/avatar', [UserController::class, 'updateAvatar'])->name('user.avatar.update');
    Route::delete('/profile/avatar', [UserController::class, 'deleteAvatar'])->name('user.avatar.delete');
    Route::get('/profile/watchlist', [UserController::class, 'watchlist'])->name('user.watchlist');
    Route::post('/profile/watchlist/add', [UserController::class, 'addToWatchlist'])->name('user.watchlist.add');
    Route::delete('/profile/watchlist/{id}', [UserController::class, 'removeFromWatchlist'])->name('user.watchlist.remove');
    Route::get('/profile/reviews', [UserController::class, 'reviews'])->name('user.reviews');
    Route::get('/profile/movies', [UserController::class, 'movies'])->name('user.movies');
    Route::get('/profile/list', [UserController::class, 'list'])->name('user.list');
});

// Authentication routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    
    // Protected route - requires authentication
    Route::middleware('auth')->group(function () {
        Route::get('/user', [AuthController::class, 'user'])->name('auth.user');
    });
});

// You can add other protected routes here
Route::middleware('auth')->group(function () {
    // User Movie Interactions
    Route::post('/movies/add', [UserMovieController::class, 'addMovie'])->name('movies.add');
    Route::post('/movies/like', [UserMovieController::class, 'toggleLike'])->name('movies.like');
    Route::post('/movies/watchlist', [UserMovieController::class, 'toggleWatchlist'])->name('movies.watchlist');
    Route::post('/movies/review', [UserMovieController::class, 'submitReview'])->name('movies.review');
    Route::get('/movies/{movieId}/status', [UserMovieController::class, 'getMovieStatus'])->name('movies.status');
    
    // User TV Show Interactions
    Route::post('/tv/add', [UserTVShowController::class, 'addShow'])->name('tv.add');
    Route::post('/tv/like', [UserTVShowController::class, 'toggleLike'])->name('tv.like');
    Route::post('/tv/watchlist', [UserTVShowController::class, 'toggleWatchlist'])->name('tv.watchlist');
    Route::post('/tv/review', [UserTVShowController::class, 'submitReview'])->name('tv.review');
    Route::get('/tv/{showId}/status', [UserTVShowController::class, 'getShowStatus'])->name('tv.status');
});
Route::middleware('auth')->group(function () {
    // Example protected routes
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
