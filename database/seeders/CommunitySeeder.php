<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserFavoriteMovie;
use App\Models\UserActivity;
use App\Models\UserFollower;
use Illuminate\Support\Facades\Hash;

class CommunitySeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Sample users with different platforms and profiles
        $users = [
            [
                'name' => 'Alex Rodriguez',
                'username' => 'cinefilealex',
                'email' => 'alex@example.com',
                'password' => Hash::make('password'),
                'bio' => 'Passionate film critic and Tarantino enthusiast. Love discovering hidden gems and independent cinema.',
                'location' => 'Los Angeles, CA',
                'platform' => 'tmdb',
                'platform_username' => 'alexcine',
                'popularity_score' => 2450,
                'total_movies_watched' => 1250,
                'total_reviews' => 85,
                'is_public' => true,
                'last_active' => now(),
                'social_links' => ['twitter' => 'https://twitter.com/cinefilealex', 'instagram' => 'https://instagram.com/alexcine']
            ],
            [
                'name' => 'Sarah Chen',
                'username' => 'sarahwatches',
                'email' => 'sarah@example.com',
                'password' => Hash::make('password'),
                'bio' => 'Horror movie aficionado and film student. Currently working on my thesis about women in horror cinema.',
                'location' => 'New York, NY',
                'platform' => 'letterboxd',
                'platform_username' => 'sarahwatches',
                'popularity_score' => 1980,
                'total_movies_watched' => 890,
                'total_reviews' => 156,
                'is_public' => true,
                'last_active' => now()->subHours(3),
                'social_links' => ['letterboxd' => 'https://letterboxd.com/sarahwatches']
            ],
            [
                'name' => 'Marcus Thompson',
                'username' => 'marcusfilms',
                'email' => 'marcus@example.com',
                'password' => Hash::make('password'),
                'bio' => 'Action and sci-fi enthusiast. Marvel superfan and collector of movie memorabilia.',
                'location' => 'Chicago, IL',
                'platform' => 'imdb',
                'platform_username' => 'marcusT',
                'popularity_score' => 3200,
                'total_movies_watched' => 1500,
                'total_reviews' => 203,
                'is_public' => true,
                'last_active' => now()->subHours(1),
                'social_links' => ['twitter' => 'https://twitter.com/marcusfilms']
            ],
            [
                'name' => 'Emma Watson',
                'username' => 'emmawatches',
                'email' => 'emma@example.com',
                'password' => Hash::make('password'),
                'bio' => 'Film production student with a love for foreign cinema and documentaries.',
                'location' => 'London, UK',
                'platform' => 'mubi',
                'platform_username' => 'emmaw',
                'popularity_score' => 1650,
                'total_movies_watched' => 720,
                'total_reviews' => 92,
                'is_public' => true,
                'last_active' => now()->subDays(2),
                'social_links' => ['instagram' => 'https://instagram.com/emmawatches']
            ],
            [
                'name' => 'David Park',
                'username' => 'davidcinama',
                'email' => 'david@example.com',
                'password' => Hash::make('password'),
                'bio' => 'Korean cinema specialist and film blogger. Love sharing recommendations for hidden gems.',
                'location' => 'Seoul, South Korea',
                'platform' => 'tmdb',
                'platform_username' => 'davidpark',
                'popularity_score' => 2100,
                'total_movies_watched' => 980,
                'total_reviews' => 134,
                'is_public' => true,
                'last_active' => now()->subHours(6),
                'social_links' => ['twitter' => 'https://twitter.com/davidcinama']
            ],
            [
                'name' => 'Lisa Martinez',
                'username' => 'lisamovies',
                'email' => 'lisa@example.com',
                'password' => Hash::make('password'),
                'bio' => 'Classic Hollywood enthusiast and vintage film collector. Golden Age of Cinema is my specialty.',
                'location' => 'San Francisco, CA',
                'platform' => 'letterboxd',
                'platform_username' => 'lisamartinez',
                'popularity_score' => 1800,
                'total_movies_watched' => 1100,
                'total_reviews' => 76,
                'is_public' => true,
                'last_active' => now()->subDays(1),
                'social_links' => ['letterboxd' => 'https://letterboxd.com/lisamartinez']
            ],
            [
                'name' => 'James Wilson',
                'username' => 'jameswatches',
                'email' => 'james@example.com',
                'password' => Hash::make('password'),
                'bio' => 'Independent filmmaker and movie reviewer. Always looking for the next great story.',
                'location' => 'Austin, TX',
                'platform' => 'imdb',
                'platform_username' => 'jameswilson',
                'popularity_score' => 2750,
                'total_movies_watched' => 1350,
                'total_reviews' => 189,
                'is_public' => true,
                'last_active' => now()->subMinutes(30),
                'social_links' => ['twitter' => 'https://twitter.com/jameswatches', 'youtube' => 'https://youtube.com/jameswatches']
            ],
            [
                'name' => 'Nina Patel',
                'username' => 'ninacinema',
                'email' => 'nina@example.com',
                'password' => Hash::make('password'),
                'bio' => 'Bollywood and world cinema enthusiast. Film festival regular and aspiring director.',
                'location' => 'Mumbai, India',
                'platform' => 'mubi',
                'platform_username' => 'ninapatel',
                'popularity_score' => 1450,
                'total_movies_watched' => 650,
                'total_reviews' => 58,
                'is_public' => true,
                'last_active' => now()->subHours(12),
                'social_links' => ['instagram' => 'https://instagram.com/ninacinema']
            ]
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);
            
            // Create some favorite movies for each user
            $this->createFavoriteMovies($user);
            
            // Create some activities
            $this->createActivities($user);
        }

        // Create follower relationships
        $this->createFollowerRelationships();
    }

    /**
     * Create favorite movies for a user
     */
    private function createFavoriteMovies(User $user): void
    {
        $movies = [
            [
                'movie_id' => 238,
                'movie_title' => 'The Godfather',
                'movie_poster' => '/3bhkrj58Vtu7enYsRolD1fZdja1.jpg',
                'user_rating' => 9.5,
                'user_review' => 'An absolute masterpiece. Coppola\'s direction and Brando\'s performance are legendary.',
                'watched_at' => now()->subDays(rand(1, 30))
            ],
            [
                'movie_id' => 424,
                'movie_title' => 'Schindler\'s List',
                'movie_poster' => '/sF1U4EUQS8YHUYjNl3pMGNIQyr0.jpg',
                'user_rating' => 9.2,
                'user_review' => 'Harrowing and beautiful. Spielberg\'s most important film.',
                'watched_at' => now()->subDays(rand(1, 60))
            ],
            [
                'movie_id' => 550,
                'movie_title' => 'Fight Club',
                'movie_poster' => '/pB8BM7pdSp6B6Ih7QZ4DrQ3PmJK.jpg',
                'user_rating' => 8.8,
                'user_review' => 'Mind-bending thriller with incredible performances from Norton and Pitt.',
                'watched_at' => now()->subDays(rand(1, 45))
            ],
            [
                'movie_id' => 13,
                'movie_title' => 'Forrest Gump',
                'movie_poster' => '/arw2vcBveWOVZr6pxd9XTd1TdQa.jpg',
                'user_rating' => 8.5,
                'user_review' => 'Heartwarming story that takes you on an emotional journey through American history.',
                'watched_at' => now()->subDays(rand(1, 90))
            ],
            [
                'movie_id' => 389,
                'movie_title' => '12 Angry Men',
                'movie_poster' => '/ow3wq89wM8qd5X7hWKxiRfsFf9C.jpg',
                'user_rating' => 9.0,
                'user_review' => 'Perfect example of how great writing and acting can create tension in a single room.',
                'watched_at' => now()->subDays(rand(1, 120))
            ],
            [
                'movie_id' => 155,
                'movie_title' => 'The Dark Knight',
                'movie_poster' => '/qJ2tW6WMUDux911r6m7haRef0WH.jpg',
                'user_rating' => 9.3,
                'user_review' => 'Heath Ledger\'s Joker is one of the greatest villain performances ever captured on film.',
                'watched_at' => now()->subDays(rand(1, 20))
            ]
        ];

        // Add 3-5 random movies per user
        $selectedMovies = collect($movies)->random(min(rand(3, 5), count($movies)));
        
        foreach ($selectedMovies as $movieData) {
            UserFavoriteMovie::create(array_merge(['user_id' => $user->id], $movieData));
        }
    }

    /**
     * Create activities for a user
     */
    private function createActivities(User $user): void
    {
        $activities = [
            [
                'activity_type' => 'favorite',
                'activity_data' => ['movie_title' => 'The Godfather'],
                'points' => 2,
                'created_at' => now()->subDays(rand(1, 30))
            ],
            [
                'activity_type' => 'review',
                'activity_data' => ['movie_title' => 'Schindler\'s List'],
                'points' => 5,
                'created_at' => now()->subDays(rand(1, 25))
            ],
            [
                'activity_type' => 'movie_watched',
                'activity_data' => ['movie_title' => 'Fight Club'],
                'points' => 3,
                'created_at' => now()->subDays(rand(1, 20))
            ],
            [
                'activity_type' => 'login',
                'activity_data' => null,
                'points' => 1,
                'created_at' => now()->subDays(rand(1, 5))
            ],
            [
                'activity_type' => 'profile_update',
                'activity_data' => null,
                'points' => 1,
                'created_at' => now()->subDays(rand(1, 15))
            ]
        ];

        // Add 3-5 random activities per user
        $selectedActivities = collect($activities)->random(min(rand(3, 5), count($activities)));
        
        foreach ($selectedActivities as $activityData) {
            UserActivity::create(array_merge(['user_id' => $user->id], $activityData));
        }
    }

    /**
     * Create follower relationships
     */
    private function createFollowerRelationships(): void
    {
        $users = User::all();
        
        foreach ($users as $user) {
            // Each user follows 2-3 other users randomly
            $otherUsers = $users->where('id', '!=', $user->id);
            if ($otherUsers->count() > 0) {
                $toFollow = $otherUsers->random(min(rand(2, 3), $otherUsers->count()));
                
                foreach ($toFollow as $followUser) {
                    UserFollower::firstOrCreate([
                        'follower_id' => $user->id,
                        'following_id' => $followUser->id
                    ]);
                }
            }
        }
    }
}