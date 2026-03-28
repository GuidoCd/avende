<?php

namespace App\Http\Controllers\Publisher;

use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyUpdateRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Property\Property;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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

        $currencies = \App\Models\Currency::where('is_active', true)->get()->map(fn($c) => [
            'code' => $c->code,
            'name' => $c->name,
            'symbol' => $c->symbol,
        ]);

        return Inertia::render('Publisher/Properties/Wizard', [
            'property' => $property,
            'propertyTypes' => $propertyTypes,
            'propertyStatuses' => $propertyStatuses,
            'features' => $features,
            'currencies' => $currencies,
        ]);
    }

    /**
     * Update the drafted property during the multi-step flow.
     */
    public function update(PropertyUpdateRequest $request, Property $property)
    {
        $validated = $request->validated();
        
        // Handle price if present
        if ($request->has('price') && $request->filled('price')) {
            $property->price = (int) ($request->price * 100); // Store in cents
            // Prevent fillable from overwriting raw price later
            $request->request->remove('price');
        }

        $property->update($validated);

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

            $manager = new ImageManager(new Driver());

            foreach ($files as $file) {
                // Determine if it's the first image to automatically make it main
                $isFirst = $property->getMedia('images')->count() === 0;

                $image = $manager->read($file);

                $encoded = $image->scaleDown(width: 1920)->toWebp(quality: 80);

                $filename = Str::slug($property->title) . '-' . uniqid() . '.webp';

                $property->addMediaFromString($encoded->toString())
                         ->usingFileName($filename) // Le decimos cómo debe llamarse
                         ->withCustomProperties(['main' => $isFirst])
                         ->toMediaCollection('images');
            }
        }

        return redirect()->back();
    }

    /**
     * Toggle the active status of the property (Publish/Unpublish outside the wizard).
     */
    public function toggleActive(PropertyUpdateRequest $request, Property $property)
    {
        if ($property->publishing_status === 'draft') {
            return response()->json(['message' => __('Cannot publish a draft property.')], 422);
        }
        
        $property->is_active = $request->input('is_active', !$property->is_active);
        $property->save();

        return redirect()->back();
    }
}
