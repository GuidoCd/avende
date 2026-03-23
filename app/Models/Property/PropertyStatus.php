<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PropertyStatus extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'slug',
        'name',
        'is_active',
        'color',
    ];

    public $translatable = ['name'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
