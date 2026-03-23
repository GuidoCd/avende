<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Feature extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'type',
        'name',
        'icon',
    ];

    public $translatable = ['name'];

    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class);
    }
}
