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

Route::inertia('/about', 'About')->name('about');

Route::middleware('guest')->group(function () {
    Route::post('login', [LoginController::class, 'store'])->name('login.store');
    Route::post('register', [RegisteredUserController::class, 'store'])->name('register.store');
    Route::inertia('admin-portal/login', 'Admin/Login')->name('admin.login');
    Route::post('admin-portal/login', [\App\Http\Controllers\Admin\Auth\AdminAuthController::class, 'store'])->name('admin.login.store');
});

Route::post('/preferences', [\App\Http\Controllers\PreferencesController::class, 'update'])->name('preferences.update');

Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:visitor'])->group(function () {
        Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::post('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/favorites', [\App\Http\Controllers\Profile\FavoriteController::class, 'index'])->name('profile.favorites');
        Route::get('/profile/security', [\App\Http\Controllers\Profile\SecurityController::class, 'show'])->name('profile.security');
        Route::get('/profile/identity-verification', [\App\Http\Controllers\Profile\IdentityVerificationController::class, 'index'])->name('profile.identity');
        Route::post('/profile/identity-verification', [\App\Http\Controllers\Profile\IdentityVerificationController::class, 'store'])->name('profile.identity.store');
    });
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    // Publisher Routes (unified to visitor)
    Route::prefix('publisher')->name('publisher.')->middleware(['role:visitor'])->group(function () {
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

    // Admin Routes
    Route::prefix('admin-portal')->name('admin.')->middleware(['role:admin|support'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/identity-verifications', [\App\Http\Controllers\Admin\IdentityVerificationController::class, 'index'])->name('identity.index');
        Route::post('/identity-verifications/{verification}/approve', [\App\Http\Controllers\Admin\IdentityVerificationController::class, 'approve'])->name('identity.approve');
        Route::post('/identity-verifications/{verification}/reject', [\App\Http\Controllers\Admin\IdentityVerificationController::class, 'reject'])->name('identity.reject');
        Route::get('/properties', [\App\Http\Controllers\Admin\PropertyController::class, 'index'])->name('properties.index');
        Route::get('/leads', [\App\Http\Controllers\Admin\LeadController::class, 'index'])->name('leads.index');
    });
});

// require __DIR__.'/settings.php';
