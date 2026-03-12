<?php

namespace App\Services\Profile;

use App\Models\Property\Favorite;
use Illuminate\Support\Collection;

class FavoriteService
{
    /**
     * Get the authenticated user's favorited properties.
     */
    public function getUserFavorites(int $userId): Collection
    {
        return Favorite::with('property')
            ->where('user_id', $userId)
            ->latest()
            ->get()
            ->map(function ($favorite) {
                return $favorite->property;
            });
    }
}
