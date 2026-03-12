<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\Property\PropertyType;
use App\Models\User;

class ZoneAlert extends Model
{
    /** @use HasFactory<\Database\Factories\ZoneAlertFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'radius_km',
        'property_type',
        'min_price',
        'max_price',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'min_price' => 'integer',
            'max_price' => 'integer',
            'property_type' => PropertyType::class,
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
