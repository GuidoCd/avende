<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Enums\Property\PropertyType;
use App\Enums\Property\PropertyStatus;
use App\Models\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Property extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\PropertyFactory> */
    use HasFactory, InteractsWithMedia, LogsActivity;

    protected static function booted(): void
    {
        static::creating(function (Property $property) {
            $property->uuid = (string) \Illuminate\Support\Str::uuid();
            $property->slug = \Illuminate\Support\Str::slug($property->title) . '-' . \Illuminate\Support\Str::random(5);
        });
    }

    protected $fillable = [
        'uuid',
        'user_id',
        'title',
        'slug',
        'description',
        'type',
        'status',
        'price',
        'currency',
        'address',
        'city',
        'neighborhood',
        'latitude',
        'longitude',
        'surface_total',
        'surface_covered',
        'rooms',
        'bathrooms',
        'garage',
        'is_active',
        'is_featured',
        'is_verified_property',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'type' => PropertyType::class,
            'status' => PropertyStatus::class,
            'price' => 'integer',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'surface_total' => 'integer',
            'surface_covered' => 'integer',
            'rooms' => 'integer',
            'bathrooms' => 'integer',
            'garage' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_verified_property' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'price', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function scopeInBounds($query, $swLat, $swLng, $neLat, $neLng)
    {
        return $query->whereBetween('latitude', [$swLat, $neLat])
                     ->whereBetween('longitude', [$swLng, $neLng]);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useDisk('public')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(400)
            ->height(300)
            ->sharpen(10);

        $this->addMediaConversion('optimized')
            ->width(1200)
            ->height(900)
            ->withResponsiveImages();
    }
}
