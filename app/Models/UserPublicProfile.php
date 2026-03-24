<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserPublicProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'public_phone',
        'public_email',
        'has_whatsapp',
        'bio',
    ];

    protected $casts = [
        'has_whatsapp' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
