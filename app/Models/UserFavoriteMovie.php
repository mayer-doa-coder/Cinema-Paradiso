<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFavoriteMovie extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'movie_id',
        'movie_title',
        'movie_poster',
        'user_rating',
        'user_review',
        'watched_at'
    ];

    protected $casts = [
        'user_rating' => 'decimal:1',
        'watched_at' => 'datetime'
    ];

    /**
     * Get the user that owns the favorite movie
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full poster URL
     */
    public function getPosterUrlAttribute()
    {
        return $this->movie_poster 
            ? "https://image.tmdb.org/t/p/w500" . $this->movie_poster
            : null;
    }
}