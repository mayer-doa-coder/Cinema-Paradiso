<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return view('index_main');  // This should load index_main.blade.php
})->name('home');

// Movie routes
Route::get('/moviegrid', [MovieController::class, 'grid'])->name('moviegrid');
Route::get('/movielist', [MovieController::class, 'list'])->name('movielist');
Route::get('/movies/search', [MovieController::class, 'search'])->name('movies.search');
Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movies.show');

// Celebrity routes
Route::get('/celebrities', function () {
    return view('celebritygrid01'); // Default celebrity page
})->name('celebrities');

Route::get('/celebritygrid01', function () {
    return view('celebritygrid01');
})->name('celebritygrid01');

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
Route::get('/blog', function () {
    return view('bloggrid');
})->name('blog');

Route::get('/bloggrid', function () {
    return view('bloggrid');
})->name('bloggrid');

Route::get('/blogdetail', function () {
    return view('blogdetail');
})->name('blogdetail');

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
