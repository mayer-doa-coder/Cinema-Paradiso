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
            // Check if user exists, if not create
            $user = User::where('username', $userData['username'])->first();
            
            if (!$user) {
                $user = User::create($userData);
            }
            
            // Check if user has 5 favorite movies, if not clear and recreate
            $movieCount = UserFavoriteMovie::where('user_id', $user->id)->count();
            
            if ($movieCount !== 5) {
                // Clear existing movies
                UserFavoriteMovie::where('user_id', $user->id)->delete();
                
                // Create 5 movies
                $this->createFavoriteMovies($user);
            }
            
            // Create some activities if they don't exist
            if (UserActivity::where('user_id', $user->id)->count() === 0) {
                $this->createActivities($user);
            }
        }

        // Create follower relationships
        $this->createFollowerRelationships();
    }

    /**
     * Create favorite movies for a user
     */
    private function createFavoriteMovies(User $user): void
    {
        // Different movie lists for different user types
        $moviesByUserType = [
            'cinefilealex' => [ // Tarantino enthusiast & indie cinema lover
                ['movie_id' => 680, 'movie_title' => 'Pulp Fiction', 'movie_poster' => '/d5iIlFn5s0ImszYzBPb8JPIfbXD.jpg', 'user_rating' => 9.8],
                ['movie_id' => 24, 'movie_title' => 'Kill Bill: Vol. 1', 'movie_poster' => '/v7TaX8kXMXs5yFFGR41guUDNcnB.jpg', 'user_rating' => 9.5],
                ['movie_id' => 73, 'movie_title' => 'American History X', 'movie_poster' => '/fXepRAYOx1qC3wju7XdDGx60775.jpg', 'user_rating' => 9.3],
                ['movie_id' => 453, 'movie_title' => 'A Beautiful Mind', 'movie_poster' => '/zwzWCmH72OSC9NA0ipoqw5Zjya8.jpg', 'user_rating' => 8.9],
                ['movie_id' => 497, 'movie_title' => 'The Green Mile', 'movie_poster' => '/velWPhVMQeQKcxggNEU8YmIo52R.jpg', 'user_rating' => 9.4],
            ],
            'sarahwatches' => [ // Horror movie aficionado
                ['movie_id' => 694, 'movie_title' => 'The Shining', 'movie_poster' => '/xazWoLealQwEgqZ89MLZklLZD3k.jpg', 'user_rating' => 9.6],
                ['movie_id' => 539, 'movie_title' => 'Psycho', 'movie_poster' => '/yzs79MaijCF9H5xV39p2n6sClLg.jpg', 'user_rating' => 9.4],
                ['movie_id' => 745, 'movie_title' => 'The Sixth Sense', 'movie_poster' => '/imdk21YOUAHnYroqYKAEm6vWRBF.jpg', 'user_rating' => 9.0],
                ['movie_id' => 1724, 'movie_title' => 'The Incredible Hulk', 'movie_poster' => '/gKzYx79y0AQTL4UAk1cBQJ3nvrm.jpg', 'user_rating' => 7.8],
                ['movie_id' => 240, 'movie_title' => 'The Godfather Part II', 'movie_poster' => '/hek3koDUyRQk7FIhPXsa6mT2Zc3.jpg', 'user_rating' => 9.7],
            ],
            'marcusfilms' => [ // Action & sci-fi, Marvel superfan
                ['movie_id' => 299536, 'movie_title' => 'Avengers: Infinity War', 'movie_poster' => '/7WsyChQLEftFiDOVTGkv3hFpyyt.jpg', 'user_rating' => 9.8],
                ['movie_id' => 299534, 'movie_title' => 'Avengers: Endgame', 'movie_poster' => '/or06FN3Dka5tukK1e9sl16pB3iy.jpg', 'user_rating' => 9.9],
                ['movie_id' => 27205, 'movie_title' => 'Inception', 'movie_poster' => '/oYuLEt3zVCKq57qu2F8dT7NIa6f.jpg', 'user_rating' => 9.7],
                ['movie_id' => 1895, 'movie_title' => 'Star Wars', 'movie_poster' => '/6FfCtAuVAW8XJjZ7eWeLibRLWTw.jpg', 'user_rating' => 9.5],
                ['movie_id' => 603, 'movie_title' => 'The Matrix', 'movie_poster' => '/f89U3ADr1oiB1s9GkdPOEpXUk5H.jpg', 'user_rating' => 9.8],
            ],
            'emmawatches' => [ // Foreign cinema & documentaries
                ['movie_id' => 129, 'movie_title' => 'Spirited Away', 'movie_poster' => '/39wmItIWsg5sZMyRUHLkWBcuVCM.jpg', 'user_rating' => 9.8],
                ['movie_id' => 4935, 'movie_title' => 'Howl\'s Moving Castle', 'movie_poster' => '/TkTPELed8v9VlqTz2HEjCUHMOT.jpg', 'user_rating' => 9.5],
                ['movie_id' => 12477, 'movie_title' => 'Grave of the Fireflies', 'movie_poster' => '/k9tv1rXZbOhH7eiCk5sUaZAGLBH.jpg', 'user_rating' => 9.6],
                ['movie_id' => 11216, 'movie_title' => 'Cinema Paradiso', 'movie_poster' => '/gCI2AeMV4IHSewhJkzsur5MEp6R.jpg', 'user_rating' => 9.7],
                ['movie_id' => 637, 'movie_title' => 'Life Is Beautiful', 'movie_poster' => '/74hLDKjD5aGYOotO6esUVaeISa2.jpg', 'user_rating' => 9.4],
            ],
            'davidcinama' => [ // Korean cinema specialist
                ['movie_id' => 496243, 'movie_title' => 'Parasite', 'movie_poster' => '/7IiTTgloJzvGI1TAYymCfbfl3vT.jpg', 'user_rating' => 9.9],
                ['movie_id' => 361743, 'movie_title' => 'Train to Busan', 'movie_poster' => '/jXDxKt5JCvl8JbxlbRWfHfHx9d1.jpg', 'user_rating' => 9.3],
                ['movie_id' => 670, 'movie_title' => 'Oldboy', 'movie_poster' => '/pWDtjs568ZfOTMbURQBYuT4Qpd7.jpg', 'user_rating' => 9.6],
                ['movie_id' => 33467, 'movie_title' => 'The Man from Nowhere', 'movie_poster' => '/jYlymmI39FpwqS3RVH0HX4BbWjR.jpg', 'user_rating' => 9.4],
                ['movie_id' => 19165, 'movie_title' => 'The Chaser', 'movie_poster' => '/5hM0D7vfSX8FtMvOCqRzvtxP9F0.jpg', 'user_rating' => 9.2],
            ],
            'lisamovies' => [ // Classic Hollywood enthusiast
                ['movie_id' => 598, 'movie_title' => 'Casablanca', 'movie_poster' => '/5K7cOHoay2mZusSLezBOY0Qxh8a.jpg', 'user_rating' => 9.8],
                ['movie_id' => 218, 'movie_title' => 'The Terminator', 'movie_poster' => '/qvktm0BHcnmDpul4Hz01GIazWPr.jpg', 'user_rating' => 9.2],
                ['movie_id' => 238, 'movie_title' => 'The Godfather', 'movie_poster' => '/3bhkrj58Vtu7enYsRolD1fZdja1.jpg', 'user_rating' => 9.9],
                ['movie_id' => 13, 'movie_title' => 'Forrest Gump', 'movie_poster' => '/arw2vcBveWOVZr6pxd9XTd1TdQa.jpg', 'user_rating' => 9.3],
                ['movie_id' => 389, 'movie_title' => '12 Angry Men', 'movie_poster' => '/ow3wq89wM8qd5X7hWKxiRfsFf9C.jpg', 'user_rating' => 9.7],
            ],
            'jameswatches' => [ // Independent filmmaker
                ['movie_id' => 550, 'movie_title' => 'Fight Club', 'movie_poster' => '/pB8BM7pdSp6B6Ih7QZ4DrQ3PmJK.jpg', 'user_rating' => 9.7],
                ['movie_id' => 157336, 'movie_title' => 'Interstellar', 'movie_poster' => '/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg', 'user_rating' => 9.8],
                ['movie_id' => 155, 'movie_title' => 'The Dark Knight', 'movie_poster' => '/qJ2tW6WMUDux911r6m7haRef0WH.jpg', 'user_rating' => 9.9],
                ['movie_id' => 424, 'movie_title' => 'Schindler\'s List', 'movie_poster' => '/sF1U4EUQS8YHUYjNl3pMGNIQyr0.jpg', 'user_rating' => 9.8],
                ['movie_id' => 278, 'movie_title' => 'The Shawshank Redemption', 'movie_poster' => '/q6y0Go1tsGEsmtFryDOJo3dEmqu.jpg', 'user_rating' => 10.0],
            ],
            'ninacinema' => [ // Bollywood & world cinema
                ['movie_id' => 19404, 'movie_title' => '3 Idiots', 'movie_poster' => '/66A9MqXOyVFCssoloscw02xfBNz.jpg', 'user_rating' => 9.7],
                ['movie_id' => 508439, 'movie_title' => 'Dangal', 'movie_poster' => '/lPsD10PP4rgUGiGR4CCXA6iY0QQ.jpg', 'user_rating' => 9.5],
                ['movie_id' => 238, 'movie_title' => 'The Godfather', 'movie_poster' => '/3bhkrj58Vtu7enYsRolD1fZdja1.jpg', 'user_rating' => 9.6],
                ['movie_id' => 637, 'movie_title' => 'Life Is Beautiful', 'movie_poster' => '/74hLDKjD5aGYOotO6esUVaeISa2.jpg', 'user_rating' => 9.4],
                ['movie_id' => 11216, 'movie_title' => 'Cinema Paradiso', 'movie_poster' => '/gCI2AeMV4IHSewhJkzsur5MEp6R.jpg', 'user_rating' => 9.8],
            ],
        ];

        $userMovies = $moviesByUserType[$user->username] ?? $moviesByUserType['cinefilealex'];
        
        foreach ($userMovies as $movieData) {
            UserFavoriteMovie::create([
                'user_id' => $user->id,
                'movie_id' => $movieData['movie_id'],
                'movie_title' => $movieData['movie_title'],
                'movie_poster' => $movieData['movie_poster'],
                'user_rating' => $movieData['user_rating'],
                'user_review' => 'An amazing film that left a lasting impression on me.',
                'watched_at' => now()->subDays(rand(1, 90))
            ]);
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