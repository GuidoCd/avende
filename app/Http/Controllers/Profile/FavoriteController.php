<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Services\Profile\FavoriteService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FavoriteController extends Controller
{
    public function __construct(
        protected FavoriteService $favoriteService
    ) {}

    /**
     * Display a listing of the user's favorited properties.
     */
    public function index(Request $request)
    {
        $favorites = $this->favoriteService->getUserFavorites($request->user()->id);

        return Inertia::render('Profile/Favorites', [
            'favorites' => $favorites,
        ]);
    }
}
