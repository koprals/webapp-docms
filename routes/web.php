<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterKlienController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->guest(backpack_url('login'));
});

Route::get('/register/klien', [RegisterKlienController::class, 'showRegistrationForm'])->name('register.klien');
Route::post('/register/klien', [RegisterKlienController::class, 'register']);
