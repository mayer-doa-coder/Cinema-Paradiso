<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMovie extends Model
{
    protected $fillable = [
        'user_id',
        'movie_id',
        'movie_title',
        'movie_poster',
        'rating',
        'release_year',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
