<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
        'bio',
        'country',
        'state',
        'phone',
        'popularity_score',
        'platform',
        'platform_username',
        'location',
        'social_links',
        'total_movies_watched',
        'total_reviews',
        'is_public',
        'last_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'social_links' => 'array',
            'last_active' => 'datetime',
        ];
    }

    /**
     * Get the user's favorite movies
     */
    public function favoriteMovies()
    {
        return $this->hasMany(UserFavoriteMovie::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the user's activities
     */
    public function activities()
    {
        return $this->hasMany(UserActivity::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get users that this user is following
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'user_followers', 'follower_id', 'following_id')
                    ->withTimestamps();
    }

    /**
     * Get users that are following this user
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_followers', 'following_id', 'follower_id')
                    ->withTimestamps();
    }

    /**
     * Get the user's avatar URL with circular styling
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            // If it's a full URL (from social platforms)
            if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
                return $this->avatar;
            }
            // If it's a local file (stored as 'avatars/filename.jpg')
            return asset('storage/' . $this->avatar);
        }
        
        // Default avatar with user's initials
        return $this->getDefaultAvatarUrl();
    }

    /**
     * Generate a default avatar URL with user's initials
     */
    public function getDefaultAvatarUrl()
    {
        $initials = strtoupper(substr($this->name, 0, 1));
        if (str_contains($this->name, ' ')) {
            $nameParts = explode(' ', $this->name);
            $initials = strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
        }
        
        // Generate avatar with initials using a service like UI Avatars
        return "https://ui-avatars.com/api/?name=" . urlencode($initials) . "&background=dd2c00&color=fff&size=200&rounded=true";
    }

    /**
     * Calculate and update popularity score
     */
    public function updatePopularityScore()
    {
        $score = 0;
        
        // Base points from activities
        $score += $this->activities()->sum('points');
        
        // Bonus points from followers
        $score += $this->followers()->count() * 10;
        
        // Bonus points from movies watched
        $score += $this->total_movies_watched * 2;
        
        // Bonus points from reviews
        $score += $this->total_reviews * 5;
        
        // Recent activity bonus (last 30 days)
        $recentActivities = $this->activities()
                                ->where('created_at', '>=', now()->subDays(30))
                                ->count();
        $score += $recentActivities * 3;
        
        $this->update(['popularity_score' => $score]);
        
        return $score;
    }

    /**
     * Check if user is following another user
     */
    public function isFollowing(User $user)
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    /**
     * Get top favorite movies (limited to 6 for display)
     */
    public function getTopFavoriteMoviesAttribute()
    {
        return $this->favoriteMovies()
                    ->orderByDesc('user_rating')
                    ->orderByDesc('created_at')
                    ->limit(6)
                    ->get();
    }

    /**
     * Scope to get popular users
     */
    public function scopePopular($query, $limit = 20)
    {
        return $query->where('is_public', true)
                    ->orderByDesc('popularity_score')
                    ->orderByDesc('last_active')
                    ->limit($limit);
    }

    /**
     * Scope to get active users
     */
    public function scopeActive($query)
    {
        return $query->where('last_active', '>=', now()->subDays(30));
    }
}
