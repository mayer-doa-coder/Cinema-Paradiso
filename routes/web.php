<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('index_main');  // This should load index_main.blade.php
})->name('home');

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
    // Example protected routes
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
