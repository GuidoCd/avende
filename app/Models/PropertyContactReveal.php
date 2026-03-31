<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Property\Property;

class PropertyContactReveal extends Model
{
    protected $fillable = [
        'property_id',
        'user_id',
        'ip_address',
        'user_agent',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
