<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;

use App\Http\Controllers\Profile\ProfileController;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware('guest')->group(function () {
    Route::post('login', [LoginController::class, 'store'])->name('login.store');
    Route::post('register', [RegisteredUserController::class, 'store'])->name('register.store');
});

Route::post('/language', [\App\Http\Controllers\LocaleController::class, 'update'])->name('locale.update');

Route::middleware(['auth'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/favorites', [\App\Http\Controllers\Profile\FavoriteController::class, 'index'])->name('profile.favorites');
    Route::get('profile/security', [\App\Http\Controllers\Profile\SecurityController::class, 'show'])->name('profile.security');
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    // Publisher Routes (Add publisher role middleware here later if needed)
    Route::prefix('publisher')->name('publisher.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Publisher\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/properties', [\App\Http\Controllers\Publisher\PropertyController::class, 'index'])->name('properties.index');
        Route::get('/properties/create', [\App\Http\Controllers\Publisher\PropertyController::class, 'create'])->name('properties.create');
        Route::post('/properties', [\App\Http\Controllers\Publisher\PropertyController::class, 'store'])->name('properties.store');
    });
});

// require __DIR__.'/settings.php';
