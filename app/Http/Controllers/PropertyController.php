<?php

namespace App\Http\Controllers;

use App\Models\Property\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PropertyController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Request $request, Property $property)
    {
        // Require active property
        if (!$property->is_active || $property->publishing_status !== 'published') {
            abort(404);
        }

        // Load necessary relations
        $property->load([
            'propertyType',
            'propertyStatus',
            'features',
            'user.publicProfile'
        ]);

        // Track view using Cache to prevent duplicates
        $user = $request->user();
        if (!$user || $user->id !== $property->user_id) {
            $identifier = $user ? "user_" . $user->id : "ip_" . md5($request->ip() . $request->userAgent());
            $cacheKey = "property_view_{$property->id}_{$identifier}";
            
            if (!\Illuminate\Support\Facades\Cache::has($cacheKey)) {
                $property->increment('views_count');
                \Illuminate\Support\Facades\Cache::put($cacheKey, true, now()->addHours(24));
            }
        }

        // Formatted agent data
        $publisher = $property->user;
        $profile = $publisher ? $publisher->publicProfile : null;
        
        $agent = null;
        if ($profile) {
            $agent = [
                'name' => $profile->name ?? $publisher->name,
                'title' => $profile->title ?? 'Agente',
                'agency' => $profile->agency_name ?? 'Vive Aquí Inmobiliaria',
                'phone' => $profile->phone ?? $publisher->phone,
                'whatsapp' => $profile->whatsapp ?? $profile->phone ?? $publisher->phone,
                'email' => $profile->email ?? $publisher->email,
                'avatar' => $profile->avatar_url ?? null, // Will use placeholder in frontend if null
                'is_verified' => (bool) $publisher->is_verified,
            ];
        } elseif ($publisher) {
            $agent = [
                'name' => $publisher->name,
                'title' => 'Agente Inmobiliario',
                'agency' => 'Independiente',
                'phone' => $publisher->phone,
                'whatsapp' => $publisher->phone,
                'email' => $publisher->email,
                'avatar' => $publisher->getFirstMediaUrl('avatar') ?: null,
                'is_verified' => (bool) $publisher->is_verified,
            ];
        }

        return Inertia::render('Properties/Show', [
            'property' => [
                'id' => $property->uuid,
                'title' => $property->title,
                'type' => $property->propertyType?->name ?? 'propiedad',
                'neighborhood' => $property->neighborhood,
                'address' => $property->address,
                'price' => $property->price,
                'isForRent' => $property->propertyStatus?->slug === 'rent',
                'status' => $property->propertyStatus?->slug,
                'maintenanceFee' => $property->common_expenses,
                'beds' => $property->rooms,
                'baths' => $property->bathrooms,
                'sqm' => $property->surface_total,
                'sqmCovered' => $property->surface_covered,
                'parkingSpots' => $property->garage,
                'lat' => (float) $property->latitude,
                'lng' => (float) $property->longitude,
                'featured' => $property->is_featured,
                'newListing' => $property->created_at->diffInDays() < 14,
                'images' => $property->getMedia('images')->map(fn($m) => $m->getUrl('optimized'))->toArray(),
                'image' => $property->getFirstMediaUrl('images', 'optimized'),
                'description' => $property->description,
                'floors' => $property->floor_number,
                'totalFloors' => $property->total_floors,
                'halfBaths' => $property->half_bathrooms,
                'age' => $property->year_built ? date('Y') - $property->year_built : null,
                'amenities' => $property->features->pluck('name')->toArray(),
                'agent' => $agent,
            ],
            'canLogin' => \Route::has('login.store'),
            'canRegister' => \Route::has('register.store'),
        ]);
    }

    /**
     * Record a contact view and notify the publisher.
     */
    public function recordContactView(Request $request, Property $property)
    {
        $user = $request->user();

        // If the property has an owner, and the viewer isn't the owner, notify them
        if (!$user || $user->id !== $property->user_id) {
            
            // Record the reveal for future analytics
            \App\Models\PropertyContactReveal::create([
                'property_id' => $property->id,
                'user_id' => $user->id ?? null,
                'ip_address' => $request->ip(),
                'user_agent' => mb_substr($request->userAgent() ?? '', 0, 500),
            ]);
        }

        return response()->json(['success' => true]);
    }
}
