<?php

namespace App\Http\Controllers\Publisher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Property\Property;

class PropertyController extends Controller
{
    /**
     * Display a listing of the properties owned by the publisher.
     */
    public function index(Request $request)
    {
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
                'publishing_status' => $property->publishing_status,
                'views_count' => $property->views_count,
                'thumbnail' => $property->getFirstMediaUrl('images', 'thumb'),
            ]);

        return Inertia::render('Publisher/Properties/Index', [
            'properties' => $properties
        ]);
    }

    /**
     * Store a newly created draft property in storage.
     */
    public function create(Request $request)
    {
        // Check for an empty draft within the last 24h to prevent spam
        $draft = Property::where('user_id', $request->user()->id)
            ->where('publishing_status', 'draft')
            ->latest()
            ->first();

        if (! $draft || $draft->created_at->diffInHours(now()) > 24) {
            $draft = Property::create([
                'user_id' => $request->user()->id,
                'publishing_status' => 'draft',
                'title' => null,
            ]);
        }

        return redirect()->route('publisher.properties.edit', $draft->uuid);
    }

    /**
     * Show the multi-step form for completing/editing a drafted property.
     */
    public function edit(Property $property)
    {
        // Eager load necessary relations for the property
        $property->load(['propertyType', 'propertyStatus', 'features', 'media']);

        // Load images
        $property->images = $property->getMedia('images')->map(fn($m) => [
            'uuid' => $m->uuid,
            'url' => $m->getUrl(),
            'is_main' => $m->getCustomProperty('main', false),
        ])->toArray();

        // Load dictionaries for the steps (Property Types, Statuses, Features)
        $propertyTypes = \App\Models\Property\PropertyType::where('is_active', true)->get()->map(fn($t) => [
            'id' => $t->id,
            'name' => $t->getTranslation('name', app()->getLocale()),
            'icon' => $t->icon,
        ]);
        
        $propertyStatuses = \App\Models\Property\PropertyStatus::where('is_active', true)->get()->map(fn($s) => [
            'id' => $s->id,
            'name' => $s->getTranslation('name', app()->getLocale()),
            'color' => $s->color,
        ]);

        $features = \App\Models\Property\Feature::all()->groupBy('type')->map(function($items) {
            return $items->map(fn($f) => [
                'id' => $f->id,
                'name' => $f->getTranslation('name', app()->getLocale()),
                'icon' => $f->icon,
            ]);
        });

        return Inertia::render('Publisher/Properties/Wizard', [
            'property' => $property,
            'propertyTypes' => $propertyTypes,
            'propertyStatuses' => $propertyStatuses,
            'features' => $features,
        ]);
    }

    /**
     * Update the drafted property during the multi-step flow.
     */
    public function update(Request $request, Property $property)
    {
        // Simple update approach accepting any property attributes configured in fillable.
        // In a real application, you'd use a FormRequest with sometimes|required validation.
        
        // Handle price if present
        if ($request->has('price') && $request->filled('price')) {
            $property->price = (int) ($request->price * 100); // Store in cents
            // Prevent fillable from overwriting raw price later
            $request->request->remove('price');
        }

        $property->update($request->all());

        if ($request->has('features')) {
            $property->features()->sync($request->features);
        }

        // Handle existing images updates (Main and Deletions)
        if ($request->has('main_image_uuid')) {
            $images = $property->getMedia('images');
            foreach ($images as $img) {
                $img->setCustomProperty('main', $img->uuid === $request->main_image_uuid);
                $img->save();
            }
        }

        if ($request->has('deleted_image_uuids') && is_array($request->deleted_image_uuids)) {
            $property->media()->whereIn('uuid', $request->deleted_image_uuids)->delete();
        }

        // Handle File Uploads if present
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            if (!is_array($files)) {
                $files = [$files];
            }
            foreach ($files as $file) {
                // Determine if it's the first image to automatically make it main
                $isFirst = $property->getMedia('images')->count() === 0;
                $property->addMedia($file)
                         ->withCustomProperties(['main' => $isFirst])
                         ->toMediaCollection('images');
            }
        }

        return redirect()->back();
    }
}
