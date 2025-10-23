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

    /**
     * Get the full poster URL
     */
    public function getPosterUrlAttribute()
    {
        if (!$this->movie_poster) {
            return null;
        }

        // If it's already a full URL, return it as is
        if (filter_var($this->movie_poster, FILTER_VALIDATE_URL)) {
            return $this->movie_poster;
        }

        // If it starts with '/', it's a TMDB path, so add the base URL
        if (strpos($this->movie_poster, '/') === 0) {
            return "https://image.tmdb.org/t/p/w500" . $this->movie_poster;
        }

        // If it's a local storage path
        if (strpos($this->movie_poster, 'storage/') === 0 || strpos($this->movie_poster, 'images/') === 0) {
            return asset($this->movie_poster);
        }

        // Default: assume it's a TMDB path without leading slash
        return "https://image.tmdb.org/t/p/w500/" . $this->movie_poster;
    }
}
