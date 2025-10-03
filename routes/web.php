<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CelebrityController;
use App\Http\Controllers\BlogController;

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('home.search');

// Movie routes
Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movies/search', [MovieController::class, 'search'])->name('movies.search');
Route::get('/movies/genre/{genreId}', [MovieController::class, 'byGenre'])->name('movies.genre');
Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movies.show');

// TV routes (placeholder for future implementation)
Route::get('/tv', function () {
    return redirect()->route('movies.index')->with('info', 'TV shows coming soon!');
})->name('tv.index');

// AJAX endpoints for movie data
Route::get('/api/movies/{id}/credits', [MovieController::class, 'getCredits'])->name('api.movies.credits');
Route::get('/api/movies/{id}/images', [MovieController::class, 'getImages'])->name('api.movies.images');
Route::get('/api/movies/{id}/videos', [MovieController::class, 'getVideos'])->name('api.movies.videos');

// Legacy movie routes (redirect to new ones)
Route::get('/moviegrid', [MovieController::class, 'grid'])->name('moviegrid');
Route::get('/movielist', [MovieController::class, 'list'])->name('movielist');

// Celebrity routes
Route::get('/celebrities', [CelebrityController::class, 'index'])->name('celebrities');

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
Route::get('/community', function () {
    return view('celebritygrid01'); // Temporary placeholder - create proper community view later
})->name('community');

// Blog/News routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog');

Route::get('/bloggrid', [BlogController::class, 'index'])->name('bloggrid');

Route::get('/blogdetail', [BlogController::class, 'detail'])->name('blogdetail');

// Help and Contact routes
Route::get('/help', function () {
    return view('help');
})->name('help');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

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
    // Add protected movie routes here if needed
    // Route::post('/movies/{id}/favorite', [MovieController::class, 'favorite'])->name('movies.favorite');
    // Route::post('/movies/{id}/rate', [MovieController::class, 'rate'])->name('movies.rate');
});
Route::middleware('auth')->group(function () {
    // Example protected routes
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
