<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_type',
        'activity_data',
        'points'
    ];

    protected $casts = [
        'activity_data' => 'array'
    ];

    /**
     * Get the user that owns the activity
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Activity types and their point values
     */
    public static function getActivityPoints($type)
    {
        $points = [
            'favorite' => 2,
            'review' => 5,
            'follow' => 1,
            'login' => 1,
            'profile_update' => 1,
            'movie_watched' => 3,
        ];

        return $points[$type] ?? 0;
    }
}