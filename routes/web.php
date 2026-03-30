<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Profile\ProfileController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/properties/{property:slug}', [\App\Http\Controllers\PropertyController::class, 'show'])->name('property.show');
Route::post('/api/properties/{property:uuid}/contact-views', [\App\Http\Controllers\PropertyController::class, 'recordContactView'])->name('property.contact-views');
Route::post('/api/properties/{property:uuid}/leads', [\App\Http\Controllers\PropertyLeadController::class, 'store'])->name('property.leads.store');

Route::middleware('guest')->group(function () {
    Route::post('login', [LoginController::class, 'store'])->name('login.store');
    Route::post('register', [RegisteredUserController::class, 'store'])->name('register.store');
});

Route::post('/language', [\App\Http\Controllers\LocaleController::class, 'update'])->name('locale.update');

Route::middleware(['auth'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('profile', [ProfileController::class, 'update'])->name('profile.update'); // Using POST instead of PUT because forms with Files require POST in Laravel unless spoofed.
    Route::get('profile/favorites', [\App\Http\Controllers\Profile\FavoriteController::class, 'index'])->name('profile.favorites');
    Route::get('profile/security', [\App\Http\Controllers\Profile\SecurityController::class, 'show'])->name('profile.security');
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    // Publisher Routes (Add publisher role middleware here later if needed)
    Route::prefix('publisher')->name('publisher.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Publisher\DashboardController::class, 'index'])->name('dashboard');
        // Publisher Leads
        Route::get('/leads', [\App\Http\Controllers\Publisher\LeadController::class, 'index'])->name('leads.index');
        
        // Publisher Profile
        Route::get('/profile', [\App\Http\Controllers\Publisher\PublisherProfileController::class, 'show'])->name('profile.show');
        Route::post('/profile', [\App\Http\Controllers\Publisher\PublisherProfileController::class, 'update'])->name('profile.update');

        Route::get('/properties', [\App\Http\Controllers\Publisher\PublisherPropertyController::class, 'index'])->name('properties.index');
        Route::get('/properties/create', [\App\Http\Controllers\Publisher\PublisherPropertyController::class, 'create'])->name('properties.create');
        Route::get('/properties/{property:uuid}/build', [\App\Http\Controllers\Publisher\PublisherPropertyController::class, 'edit'])->name('properties.edit');
        Route::put('/properties/{property:uuid}', [\App\Http\Controllers\Publisher\PublisherPropertyController::class, 'update'])->name('properties.update');
        Route::patch('/properties/{property:uuid}/toggle', [\App\Http\Controllers\Publisher\PublisherPropertyController::class, 'toggleActive'])->name('properties.toggle');
    });
});

// require __DIR__.'/settings.php';
