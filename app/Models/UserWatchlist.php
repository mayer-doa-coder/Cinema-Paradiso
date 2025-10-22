<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserWatchlist extends Model
{
    protected $table = 'user_watchlist';
    
    protected $fillable = [
        'user_id',
        'movie_id',
        'movie_title',
        'movie_poster',
        'release_year',
        'media_type',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
