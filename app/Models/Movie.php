<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'year',
        'rating',
        'description',
        'runtime',
        'mpaa_rating',
        'release_date',
        'director',
        'stars',
        'poster_image',
        'genre',
        'featured',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'release_date' => 'date',
        'featured' => 'boolean',
        'rating' => 'decimal:1',
        'runtime' => 'integer',
        'year' => 'integer',
    ];

    /**
     * Get the URL for the movie's poster image.
     *
     * @return string
     */
    public function getPosterUrlAttribute()
    {
        return $this->poster_image ? asset('images/uploads/' . $this->poster_image) : asset('images/uploads/default-movie.jpg');
    }

    /**
     * Get the movie's formatted runtime.
     *
     * @return string
     */
    public function getFormattedRuntimeAttribute()
    {
        if (!$this->runtime) {
            return 'N/A';
        }
        
        $hours = floor($this->runtime / 60);
        $minutes = $this->runtime % 60;
        
        return $hours . 'h' . ($minutes > 0 ? $minutes . '\'' : '');
    }

    /**
     * Scope a query to only include featured movies.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Scope a query to filter by genre.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $genre
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByGenre($query, $genre)
    {
        return $query->where('genre', 'like', '%' . $genre . '%');
    }

    /**
     * Scope a query to filter by rating range.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float $min
     * @param float $max
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByRating($query, $min = null, $max = null)
    {
        if ($min !== null) {
            $query->where('rating', '>=', $min);
        }
        
        if ($max !== null) {
            $query->where('rating', '<=', $max);
        }
        
        return $query;
    }

    /**
     * Scope a query to filter by year range.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $from
     * @param int $to
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByYear($query, $from = null, $to = null)
    {
        if ($from !== null) {
            $query->where('year', '>=', $from);
        }
        
        if ($to !== null) {
            $query->where('year', '<=', $to);
        }
        
        return $query;
    }
}