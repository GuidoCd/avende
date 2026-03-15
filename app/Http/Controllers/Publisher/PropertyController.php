<?php

namespace App\Http\Controllers\Publisher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Property\Property;

use App\Http\Requests\Publisher\StorePropertyRequest;

class PropertyController extends Controller
{
    /**
     * Display a listing of the properties owned by the publisher.
     */
    public function index(Request $request)
    {
        // Simple fetch of the user's properties. In reality, we'd paginate.
        $properties = Property::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10)
            ->through(fn($property) => [
                'id' => $property->id,
                'uuid' => $property->uuid,
                'title' => $property->title,
                'address' => $property->address,
                'price' => $property->price,
                'currency' => $property->currency,
                'is_active' => $property->is_active,
                'views_count' => $property->views_count,
                'thumbnail' => $property->getFirstMediaUrl('images', 'thumb'),
            ]);

        return Inertia::render('Publisher/Properties/Index', [
            'properties' => $properties
        ]);
    }

    /**
     * Show the form for creating a new property.
     */
    public function create()
    {
        return Inertia::render('Publisher/Properties/Create');
    }

    /**
     * Store a newly created property in storage.
     */
    public function store(StorePropertyRequest $request)
    {
        $validated = $request->validated();
        
        // Convert price to cents
        $validated['price'] = (int) ($validated['price'] * 100);
        $validated['user_id'] = $request->user()->id;
        
        // Hardcode city for now or extract from coords/address if needed
        $validated['city'] = 'Santa Cruz'; 

        $property = Property::create($validated);

        // Handle Image Uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $property->addMedia($file)
                    ->toMediaCollection('images');
            }
        }

        return redirect()->route('publisher.properties.index')
            ->with('success', __('Property created successfully.'));
    }
}
