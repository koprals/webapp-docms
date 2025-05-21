<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\Auth\RegisterKlienController;

// Set Hompage
Route::get('homepage', [HomepageController::class, 'index'])->name('homepage');

// Redirect to Homepage
Route::get('/', function () {
    return redirect()->route('homepage');
});

// Register Klien Route
Route::get('/register/klien', [RegisterKlienController::class, 'showRegistrationForm'])->name('register.klien');
Route::post('/register/klien', [RegisterKlienController::class, 'register']);

// Route::get('/', function () {
//     if (Auth::check()) {
//         return redirect()->route('dashboard');
//     }
//     return redirect()->guest(backpack_url('login'));
// });
