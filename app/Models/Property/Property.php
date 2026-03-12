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

class Property extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\PropertyFactory> */
    use HasFactory, InteractsWithMedia, LogsActivity;

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
}
