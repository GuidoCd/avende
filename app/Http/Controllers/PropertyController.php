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

        // Track view
        $property->increment('views_count');

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

        // Increment contact reveals
        // $property->increment('contact_reveals_count'); // If such column exists

        // If the property has an owner, and the viewer isn't the owner, notify them
        if ($property->user_id && (!$user || $user->id !== $property->user_id)) {
            // Notify the publisher
            // This is a placeholder for where the actual email/notification logic goes
            // e.g. $property->user->notify(new PropertyPhoneRevealed($property, $user));
            // "¡Buenas noticias! El usuario Juan Pérez (juan@email.com) acaba de ver el teléfono de tu propiedad en Cochabamba."
            \Log::info("El usuario " . ($user->name ?? 'Invitado') . " (" . ($user->email ?? 'N/A') . ") acaba de ver el teléfono de tu propiedad en {$property->city}.", [
                'property_id' => $property->id,
                'user_id' => $user->id ?? null,
            ]);
        }

        return response()->json(['success' => true]);
    }
}
