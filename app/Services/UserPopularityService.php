<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Log;

class UserPopularityService
{
    /**
     * Update popularity scores for all users
     *
     * @return int Number of users updated
     */
    public function updateAllPopularityScores(): int
    {
        $users = User::where('is_public', true)->get();
        $updated = 0;

        foreach ($users as $user) {
            try {
                $this->updateUserPopularityScore($user);
                $updated++;
            } catch (\Exception $e) {
                Log::error("Failed to update popularity for user {$user->id}: " . $e->getMessage());
            }
        }

        Log::info("Updated popularity scores for {$updated} users");
        return $updated;
    }

    /**
     * Update popularity score for a specific user
     *
     * @param User $user
     * @return int The new popularity score
     */
    public function updateUserPopularityScore(User $user): int
    {
        $score = 0;
        
        // Base points from activities
        $score += $user->activities()->sum('points');
        
        // Bonus points from followers (10 points per follower)
        $score += $user->followers()->count() * 10;
        
        // Bonus points from movies watched (2 points per movie)
        $score += $user->total_movies_watched * 2;
        
        // Bonus points from reviews (5 points per review)
        $score += $user->total_reviews * 5;
        
        // Recent activity bonus (last 30 days) - 3 points per activity
        $recentActivities = $user->activities()
                                ->where('created_at', '>=', now()->subDays(30))
                                ->count();
        $score += $recentActivities * 3;
        
        // Bonus for having complete profile
        if ($user->bio && $user->location && $user->avatar) {
            $score += 50; // Complete profile bonus
        }
        
        // Platform integration bonus
        if ($user->platform && $user->platform_username) {
            $score += 25; // Platform connection bonus
        }
        
        // Social links bonus
        if ($user->social_links && count($user->social_links) > 0) {
            $score += count($user->social_links) * 15; // 15 points per social link
        }
        
        // Recent login bonus
        if ($user->last_active && $user->last_active->gte(now()->subDays(7))) {
            $score += 20; // Active user bonus
        }
        
        $user->update(['popularity_score' => $score]);
        
        return $score;
    }

    /**
     * Record a user activity and update popularity
     *
     * @param User $user
     * @param string $activityType
     * @param array|null $activityData
     * @return UserActivity
     */
    public function recordActivity(User $user, string $activityType, ?array $activityData = null): UserActivity
    {
        $points = UserActivity::getActivityPoints($activityType);
        
        $activity = UserActivity::create([
            'user_id' => $user->id,
            'activity_type' => $activityType,
            'activity_data' => $activityData,
            'points' => $points
        ]);

        // Update user's last active timestamp
        $user->update(['last_active' => now()]);

        // Update popularity score
        $this->updateUserPopularityScore($user);

        return $activity;
    }

    /**
     * Get top users by popularity
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopUsers(int $limit = 20)
    {
        return User::popular($limit)
                  ->with(['favoriteMovies', 'followers', 'following'])
                  ->get();
    }

    /**
     * Get users by platform
     *
     * @param string $platform
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUsersByPlatform(string $platform, int $limit = 20)
    {
        return User::where('is_public', true)
                  ->where('platform', $platform)
                  ->orderByDesc('popularity_score')
                  ->limit($limit)
                  ->with(['favoriteMovies', 'followers', 'following'])
                  ->get();
    }

    /**
     * Get trending users (most activity in last 7 days)
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTrendingUsers(int $limit = 20)
    {
        $userIds = UserActivity::where('created_at', '>=', now()->subDays(7))
                              ->selectRaw('user_id, COUNT(*) as activity_count')
                              ->groupBy('user_id')
                              ->orderByDesc('activity_count')
                              ->limit($limit)
                              ->pluck('user_id');

        return User::whereIn('id', $userIds)
                  ->where('is_public', true)
                  ->with(['favoriteMovies', 'followers', 'following'])
                  ->get()
                  ->sortByDesc(function($user) use ($userIds) {
                      return $userIds->search($user->id);
                  });
    }

    /**
     * Calculate platform diversity score
     *
     * @return array
     */
    public function getPlatformStats(): array
    {
        $platforms = User::where('is_public', true)
                        ->whereNotNull('platform')
                        ->selectRaw('platform, COUNT(*) as count')
                        ->groupBy('platform')
                        ->orderByDesc('count')
                        ->get();

        return [
            'platforms' => $platforms,
            'total_connected' => $platforms->sum('count'),
            'total_users' => User::where('is_public', true)->count(),
            'connection_rate' => $platforms->sum('count') / max(User::where('is_public', true)->count(), 1) * 100
        ];
    }
}