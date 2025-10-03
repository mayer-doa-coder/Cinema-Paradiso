@extends('layouts.app')

@section('title', $user->name . ' - Community Profile')

@section('content')

@push('styles')
<style>
/* Remove white border/outline from navigation buttons */
header .navbar-default .navbar-nav li a,
header .navbar-default .navbar-nav li.btn a,
header .navbar-default .navbar-nav li a:focus,
header .navbar-default .navbar-nav li a:active,
header .navbar-default .navbar-nav li.btn a:focus,
header .navbar-default .navbar-nav li.btn a:active {
    outline: none !important;
    border: none !important;
    box-shadow: none !important;
    background-color: transparent !important;
}

/* Specific fix for sign up button */
header .navbar-default .navbar-nav li.btn a {
    background-color: #ec6eab !important;
}

/* Maintain hover effects */
header .navbar-default .navbar-nav li a:hover {
    color: #e9d736 !important;
}

header .navbar-default .navbar-nav li.btn a:hover {
    background-color: #d55a98 !important;
}

/* Remove focus ring from all buttons and links */
*:focus {
    outline: none !important;
}

button:focus,
a:focus,
input:focus,
select:focus,
textarea:focus {
    outline: none !important;
    box-shadow: none !important;
}
</style>
@endpush

<!-- BEGIN | Header -->
<header class="ht-header">
    <div class="container">
        <nav class="navbar navbar-default navbar-custom">
                <div class="navbar-header logo">
                    <div class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <div id="nav-icon1">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <a href="{{ route('home') }}"><img class="logo" src="{{ asset('images/cinema_paradiso.png') }}" alt="" width="119" height="58"></a>
                </div>
                <div class="collapse navbar-collapse flex-parent" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav flex-child-menu menu-left">
                        <li class="hidden">
                            <a href="#page-top"></a>
                        </li>
                        <li class="first">
                            <a class="btn btn-default lv1" href="{{ route('home') }}">
                            Home
                            </a>
                        </li>
                        <li class="first">
                            <a class="btn btn-default lv1" href="{{ route('movies.index') }}">
                            Movies
                            <!-- <ul class="sub-menu">
                                <li><a href="{{ route('movies.index', ['category' => 'popular']) }}">Popular</a></li>
                                <li><a href="{{ route('movies.index', ['category' => 'top-rated']) }}">Top Rated</a></li>
                                <li><a href="{{ route('movies.index', ['category' => 'trending']) }}">Trending</a></li>
                                <li><a href="{{ route('movies.index', ['category' => 'upcoming']) }}">Upcoming</a></li>
                            </ul> -->
                            </a>
                        </li>
                        <li class="first">
                            <a class="btn btn-default lv1" href="{{ route('celebrities') }}">
                            Celebrities
                            </a>
                        </li>
                        <li class="first">
                            <a class="btn btn-default lv1" href="{{ route('blog') }}">
                            News
                            </a>
                        </li>
                        <li class="first active">
                            <a class="btn btn-default lv1" href="{{ route('community') }}">
                            Community
                            </a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav flex-child-menu menu-right">               
                        <li><a href="{{ route('help') }}">Help</a></li>
                        @auth
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    {{ Auth::user()->name }} <i class="fa fa-angle-down" aria-hidden="true"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Profile</a></li>
                                    <li><a href="#" onclick="logout()">Logout</a></li>
                                </ul>
                            </li>
                        @else
                            <li class="loginLink"><a href="#">LOG In</a></li>
                            <li class="btn signupLink"><a href="#">sign up</a></li>
                        @endauth
                    </ul>
                </div>
        </nav>
        
        <!-- top search form -->
        <div class="top-search">
            <div class="search-dropdown">
                <i class="ion-ios-list-outline"></i>
                <select id="search-type">
                    <option value="movies">Movies</option>
                    <option value="tvshows">TV Shows</option>
                </select>
            </div>
            <div class="search-input">
                <input type="text" id="search-query" placeholder="Search for a movie, TV Show that you are looking for">
                <i class="ion-ios-search" id="search-icon" style="cursor: pointer;"></i>
            </div>
        </div>
    </div>
</header>
<!-- END | Header -->

<div class="hero mv-single-hero">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- User Profile Header -->
                <div class="profile-header">
                    <div class="profile-avatar-container">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="profile-avatar-circle">
                        @if($user->last_active && $user->last_active->gt(now()->subHours(24)))
                            <span class="profile-online-indicator"></span>
                        @endif
                    </div>
                    
                    <div class="profile-info">
                        <h1 class="profile-name">{{ $user->name }}</h1>
                        <p class="profile-username">@{{ $user->username }}</p>
                        
                        @if($user->location)
                            <p class="profile-location">
                                <i class="ion-location"></i> {{ $user->location }}
                            </p>
                        @endif
                        
                        @if($user->platform)
                            <p class="profile-platform">
                                <i class="ion-link"></i> {{ ucfirst($user->platform) }}
                                @if($user->platform_username)
                                    ({{ $user->platform_username }})
                                @endif
                            </p>
                        @endif
                        
                        @if($user->bio)
                            <p class="profile-bio">{{ $user->bio }}</p>
                        @endif
                        
                        <div class="profile-stats">
                            <div class="stat-box">
                                <span class="stat-number">{{ $stats['popularity_score'] }}</span>
                                <span class="stat-label">Popularity Points</span>
                            </div>
                            <div class="stat-box">
                                <span class="stat-number">{{ $stats['followers_count'] }}</span>
                                <span class="stat-label">Followers</span>
                            </div>
                            <div class="stat-box">
                                <span class="stat-number">{{ $stats['following_count'] }}</span>
                                <span class="stat-label">Following</span>
                            </div>
                            <div class="stat-box">
                                <span class="stat-number">{{ $stats['movies_watched'] }}</span>
                                <span class="stat-label">Movies Watched</span>
                            </div>
                            <div class="stat-box">
                                <span class="stat-number">{{ $stats['reviews_count'] }}</span>
                                <span class="stat-label">Reviews</span>
                            </div>
                        </div>
                        
                        <div class="profile-meta">
                            <p><strong>Member since:</strong> {{ $stats['member_since'] }}</p>
                            <p><strong>Last active:</strong> {{ $stats['last_active'] }}</p>
                        </div>
                        
                        @if($user->social_links)
                            <div class="social-links">
                                @foreach($user->social_links as $platform => $url)
                                    <a href="{{ $url }}" target="_blank" class="social-link">
                                        <i class="ion-social-{{ strtolower($platform) }}"></i>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-single">
    <div class="container">
        <div class="row ipad-width2">
            <div class="col-md-8 col-sm-12 col-xs-12">
                @if($error)
                    <div class="alert alert-danger">{{ $error }}</div>
                @endif
                
                <!-- Navigation Tabs -->
                <div class="profile-tabs">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#favorite-movies" data-toggle="tab">Favorite Movies</a></li>
                        <li><a href="#recent-activity" data-toggle="tab">Recent Activity</a></li>
                        <li><a href="#reviews" data-toggle="tab">Reviews</a></li>
                    </ul>
                    
                    <div class="tab-content">
                        <!-- Favorite Movies Tab -->
                        <div class="tab-pane active" id="favorite-movies">
                            <div class="profile-section">
                                <h3>Favorite Movies ({{ $favoriteMovies->total() }})</h3>
                                
                                @if($favoriteMovies->count() > 0)
                                    <div class="favorite-movies-grid">
                                        @foreach($favoriteMovies as $movie)
                                            <div class="favorite-movie-card">
                                                <div class="movie-poster">
                                                    @if($movie->movie_poster)
                                                        <img src="{{ $movie->poster_url }}" alt="{{ $movie->movie_title }}">
                                                    @else
                                                        <div class="movie-poster-placeholder">
                                                            {{ substr($movie->movie_title, 0, 2) }}
                                                        </div>
                                                    @endif
                                                    
                                                    @if($movie->user_rating)
                                                        <div class="user-rating">
                                                            <i class="ion-star"></i> {{ $movie->user_rating }}
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <div class="movie-info">
                                                    <h4>{{ $movie->movie_title }}</h4>
                                                    
                                                    @if($movie->user_review)
                                                        <p class="user-review">{{ Str::limit($movie->user_review, 100) }}</p>
                                                    @endif
                                                    
                                                    @if($movie->watched_at)
                                                        <p class="watched-date">
                                                            <i class="ion-calendar"></i> 
                                                            Watched {{ $movie->watched_at->format('M d, Y') }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <!-- Pagination -->
                                    @if($favoriteMovies->hasPages())
                                        <div class="favorite-movies-pagination">
                                            {{ $favoriteMovies->links() }}
                                        </div>
                                    @endif
                                @else
                                    <div class="no-content">
                                        <p>{{ $user->name }} hasn't added any favorite movies yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Recent Activity Tab -->
                        <div class="tab-pane" id="recent-activity">
                            <div class="profile-section">
                                <h3>Recent Activity</h3>
                                
                                @if($recentActivities->count() > 0)
                                    <div class="activity-timeline">
                                        @foreach($recentActivities as $activity)
                                            <div class="activity-item">
                                                <div class="activity-icon">
                                                    @switch($activity->activity_type)
                                                        @case('favorite')
                                                            <i class="ion-heart"></i>
                                                            @break
                                                        @case('review')
                                                            <i class="ion-compose"></i>
                                                            @break
                                                        @case('follow')
                                                            <i class="ion-person-add"></i>
                                                            @break
                                                        @case('movie_watched')
                                                            <i class="ion-play"></i>
                                                            @break
                                                        @default
                                                            <i class="ion-activity"></i>
                                                    @endswitch
                                                </div>
                                                
                                                <div class="activity-content">
                                                    <div class="activity-description">
                                                        @switch($activity->activity_type)
                                                            @case('favorite')
                                                                Added a movie to favorites
                                                                @break
                                                            @case('review')
                                                                Wrote a movie review
                                                                @break
                                                            @case('follow')
                                                                Started following someone
                                                                @break
                                                            @case('movie_watched')
                                                                Watched a movie
                                                                @break
                                                            @default
                                                                {{ ucfirst(str_replace('_', ' ', $activity->activity_type)) }}
                                                        @endswitch
                                                        
                                                        @if($activity->activity_data && isset($activity->activity_data['movie_title']))
                                                            - <strong>{{ $activity->activity_data['movie_title'] }}</strong>
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="activity-time">
                                                        {{ $activity->created_at->diffForHumans() }}
                                                        <span class="activity-points">+{{ $activity->points }} points</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="no-content">
                                        <p>No recent activity to show.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Reviews Tab -->
                        <div class="tab-pane" id="reviews">
                            <div class="profile-section">
                                <h3>Movie Reviews</h3>
                                
                                @php
                                    $reviewedMovies = $favoriteMovies->filter(function($movie) {
                                        return !empty($movie->user_review);
                                    });
                                @endphp
                                
                                @if($reviewedMovies->count() > 0)
                                    <div class="reviews-list">
                                        @foreach($reviewedMovies as $movie)
                                            <div class="review-card">
                                                <div class="review-movie">
                                                    @if($movie->movie_poster)
                                                        <img src="{{ $movie->poster_url }}" alt="{{ $movie->movie_title }}" class="review-poster">
                                                    @endif
                                                    
                                                    <div class="review-movie-info">
                                                        <h4>{{ $movie->movie_title }}</h4>
                                                        @if($movie->user_rating)
                                                            <div class="review-rating">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    <i class="ion-star{{ $i <= $movie->user_rating ? '' : '-outline' }}"></i>
                                                                @endfor
                                                                <span>{{ $movie->user_rating }}/5</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="review-content">
                                                    <p>{{ $movie->user_review }}</p>
                                                    
                                                    <div class="review-meta">
                                                        @if($movie->watched_at)
                                                            <span>Watched {{ $movie->watched_at->format('M d, Y') }}</span>
                                                        @endif
                                                        <span>Added {{ $movie->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="no-content">
                                        <p>{{ $user->name }} hasn't written any movie reviews yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="sidebar">
                    <!-- Dynamic Wallpaper -->
                    <div class="ads">
                        @if(isset($randomWallpaper) && !empty($randomWallpaper['backdrop_url']))
                            <div class="movie-wallpaper" style="position: relative; width: 336px; height: 296px; overflow: hidden; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.3);">
                                <img src="{{ $randomWallpaper['backdrop_url'] }}" alt="{{ $randomWallpaper['title'] ?? 'Movie Wallpaper' }}" 
                                     style="width: 100%; height: 100%; object-fit: cover;">
                                <div class="wallpaper-overlay" style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.8)); color: white; padding: 15px;">
                                    <h5 style="margin: 0; font-size: 14px; font-weight: bold;">{{ $randomWallpaper['title'] ?? 'Featured Movie' }}</h5>
                                    @if(!empty($randomWallpaper['overview']))
                                        <p style="margin: 5px 0 0; font-size: 11px; opacity: 0.9;">{{ $randomWallpaper['overview'] }}</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <img src="{{ asset('images/uploads/ads1.png') }}" alt="" width="336" height="296">
                        @endif
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="profile-quick-stats">
                        <h4 class="sb-title">Profile Statistics</h4>
                        <div class="quick-stat">
                            <span class="quick-stat-label">Popularity Rank</span>
                            <span class="quick-stat-value">#{{ $user->popularity_score }}</span>
                        </div>
                        <div class="quick-stat">
                            <span class="quick-stat-label">Movies This Month</span>
                            <span class="quick-stat-value">{{ $favoriteMovies->where('created_at', '>=', now()->subMonth())->count() }}</span>
                        </div>
                        <div class="quick-stat">
                            <span class="quick-stat-label">Reviews This Year</span>
                            <span class="quick-stat-value">{{ $favoriteMovies->where('created_at', '>=', now()->subYear())->whereNotNull('user_review')->count() }}</span>
                        </div>
                    </div>
                    
                    <!-- Back to Community -->
                    <div class="back-to-community">
                        <a href="{{ route('community') }}" class="btn btn-primary btn-block">
                            <i class="ion-arrow-left-c"></i> Back to Community
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-header {
    display: flex;
    align-items: center;
    gap: 30px;
    padding: 40px 0;
    color: white;
}

.profile-avatar-container {
    position: relative;
}

.profile-avatar-circle {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    border: 5px solid #dd2c00;
    object-fit: cover;
}

.profile-online-indicator {
    position: absolute;
    bottom: 10px;
    right: 10px;
    width: 25px;
    height: 25px;
    background: #4caf50;
    border-radius: 50%;
    border: 4px solid white;
}

.profile-info {
    flex: 1;
}

.profile-name {
    font-size: 2.5em;
    margin-bottom: 5px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
}

.profile-username {
    font-size: 1.2em;
    opacity: 0.8;
    margin-bottom: 10px;
}

.profile-location, .profile-platform {
    color: rgba(255,255,255,0.9);
    margin: 5px 0;
}

.profile-bio {
    font-size: 1.1em;
    margin: 15px 0;
    font-style: italic;
    opacity: 0.9;
}

.profile-stats {
    display: flex;
    gap: 20px;
    margin: 20px 0;
}

.stat-box {
    text-align: center;
    background: rgba(255,255,255,0.1);
    padding: 15px;
    border-radius: 8px;
    min-width: 80px;
}

.stat-number {
    display: block;
    font-size: 1.5em;
    font-weight: bold;
    color: #dd2c00;
}

.stat-label {
    display: block;
    font-size: 0.8em;
    opacity: 0.8;
}

.profile-meta {
    margin: 15px 0;
    font-size: 0.9em;
    opacity: 0.8;
}

.social-links {
    margin-top: 15px;
}

.social-link {
    display: inline-block;
    margin-right: 10px;
    color: white;
    font-size: 1.5em;
    transition: color 0.3s;
}

.social-link:hover {
    color: #dd2c00;
}

.profile-tabs {
    margin-top: 30px;
}

.favorite-movies-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.favorite-movie-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.favorite-movie-card:hover {
    transform: translateY(-3px);
}

.movie-poster {
    position: relative;
    height: 200px;
}

.movie-poster img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.movie-poster-placeholder {
    width: 100%;
    height: 100%;
    background: #dd2c00;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2em;
    font-weight: bold;
}

.user-rating {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0,0,0,0.8);
    color: white;
    padding: 5px 8px;
    border-radius: 15px;
    font-size: 0.8em;
}

.movie-info {
    padding: 15px;
}

.movie-info h4 {
    margin: 0 0 10px 0;
    font-size: 1.1em;
}

.user-review {
    font-size: 0.9em;
    color: #666;
    font-style: italic;
    margin: 8px 0;
}

.watched-date {
    font-size: 0.8em;
    color: #888;
    margin: 5px 0 0 0;
}

.activity-timeline {
    margin-top: 20px;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 15px;
    border-bottom: 1px solid #eee;
}

.activity-icon {
    width: 40px;
    height: 40px;
    background: #dd2c00;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2em;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
}

.activity-description {
    font-weight: 500;
    margin-bottom: 5px;
}

.activity-time {
    font-size: 0.8em;
    color: #666;
}

.activity-points {
    color: #dd2c00;
    font-weight: bold;
    margin-left: 10px;
}

.reviews-list {
    margin-top: 20px;
}

.review-card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.review-movie {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.review-poster {
    width: 60px;
    height: 90px;
    border-radius: 5px;
    object-fit: cover;
}

.review-movie-info h4 {
    margin: 0;
}

.review-rating {
    margin-top: 5px;
    color: #ffc107;
}

.review-content p {
    margin-bottom: 10px;
    line-height: 1.6;
}

.review-meta {
    font-size: 0.8em;
    color: #666;
}

.profile-quick-stats {
    margin-top: 30px;
}

.quick-stat {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.quick-stat-label {
    color: #666;
}

.quick-stat-value {
    font-weight: bold;
    color: #dd2c00;
}

.back-to-community {
    margin-top: 30px;
}

.no-content {
    text-align: center;
    padding: 40px;
    color: #666;
}

@media (max-width: 768px) {
    .profile-header {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }
    
    .profile-stats {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .favorite-movies-grid {
        grid-template-columns: 1fr;
    }
}
</style>

@push('scripts')
<script>
// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchIcon = document.getElementById('search-icon');
    const searchInput = document.getElementById('search-query');
    const searchType = document.getElementById('search-type');
    
    function performSearch() {
        const query = searchInput.value.trim();
        const type = searchType.value;
        
        if (query) {
            const searchUrl = `{{ route('home.search') }}?q=${encodeURIComponent(query)}&type=${type}`;
            window.location.href = searchUrl;
        }
    }
    
    // Search on icon click
    searchIcon.addEventListener('click', performSearch);
    
    // Search on Enter key press
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
});

async function logout() {
    try {
        const response = await fetch('/auth/logout', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Logout successful!');
            window.location.reload();
        } else {
            alert('Logout failed');
        }
    } catch (error) {
        alert('An error occurred during logout');
    }
}
</script>
@endpush

@endsection