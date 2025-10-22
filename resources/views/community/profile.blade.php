@extends('layouts.app')

@section('title', $user->name . ' - Community Profile')

@push('styles')
<style>
body {
    margin: 0 !important;
    padding: 0 !important;
    background: #020d18 !important;
}

.ht-header {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

/* Hero Section */
.user-hero {
    background: url('{{ asset('images/uploads/user-hero-bg.jpg') }}') no-repeat center;
    background-size: cover;
    padding: 60px 0 40px;
    position: relative;
    margin-top: 0;
}

.user-hero:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(11, 26, 42, 0.95), rgba(2, 13, 24, 0.95));
}

.user-hero .hero-ct {
    position: relative;
    z-index: 2;
    text-align: center;
}

.user-hero h1 {
    color: #ffffff;
    font-size: 2.5em;
    margin-bottom: 10px;
    font-weight: 700;
}

.user-hero .breadcumb {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    justify-content: center;
    gap: 10px;
}

.user-hero .breadcumb li {
    color: #abb7c4;
}

.user-hero .breadcumb li a {
    color: #dcf836;
}

/* Main Content Area */
.page-single {
    background: #020d18;
    padding: 40px 0;
}

/* Remove white border/outline */
*:focus {
    outline: none !important;
    box-shadow: none !important;
}
</style>
@endpush

@section('content')

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
                            <li>
                                <a href="{{ route('user.profile') }}" style="color: #dcf836; font-weight: 500;">
                                    {{ Auth::user()->name }}
                                </a>
                            </li>
                        @else
                            <li class="loginLink"><a href="#">LOG In</a></li>
                            <li class="btn signupLink"><a href="#">sign up</a></li>
                        @endauth
                    </ul>
                </div>
        </nav>
        
        @include('partials._search')
    </div>
</header>
<!-- END | Header -->

<div class="hero user-hero">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hero-ct">
                    <h1>{{ $user->name }}'s Profile</h1>
                    <ul class="breadcumb">
                        <li class="active"><a href="{{ route('home') }}">Home</a></li>
                        <li><span class="ion-ios-arrow-right"></span></li>
                        <li><a href="{{ route('community') }}">Community</a></li>
                        <li><span class="ion-ios-arrow-right"></span></li>
                        <li>{{ $user->username }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-single">
    <div class="container">
        <div class="row ipad-width">
            <div class="col-md-3 col-sm-12 col-xs-12">
                <!-- Sidebar with User Info -->
                <div class="user-information">
                    <div class="user-img">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                        @if($user->last_active && $user->last_active->gt(now()->subHours(24)))
                            <div class="online-status" style="width: 20px; height: 20px; background: #4caf50; border-radius: 50%; position: absolute; bottom: 70px; right: calc(50% - 60px); border: 3px solid #0b1a2a;"></div>
                        @endif
                        <h3 style="color: #ffffff; margin: 10px 0 5px;">{{ $user->name }}</h3>
                        <p style="color: #dcf836; margin: 0;">@{{ $user->username }}</p>
                    </div>
                    
                    <div class="user-fav">
                        <p>Profile Information</p>
                        <ul>
                            @if($user->location)
                                <li><i class="ion-location"></i> {{ $user->location }}</li>
                            @endif
                            @if($user->platform)
                                <li><i class="ion-link"></i> {{ ucfirst($user->platform) }}
                                    @if($user->platform_username)
                                        ({{ $user->platform_username }})
                                    @endif
                                </li>
                            @endif
                            <li><i class="ion-calendar"></i> Member since {{ $stats['member_since'] }}</li>
                            <li><i class="ion-clock"></i> Last active {{ $stats['last_active'] }}</li>
                        </ul>
                    </div>
                    
                    @if($user->bio)
                        <div class="user-fav">
                            <p>Bio</p>
                            <p style="color: #abb7c4; font-style: italic; padding: 10px 15px; line-height: 1.6;">{{ $user->bio }}</p>
                        </div>
                    @endif
                    
                    <div class="user-fav">
                        <p>Statistics</p>
                        <ul>
                            <li><i class="ion-trophy"></i> Popularity: {{ $stats['popularity_score'] }} points</li>
                            <li><i class="ion-ios-people"></i> Followers: {{ $stats['followers_count'] }}</li>
                            <li><i class="ion-person-stalker"></i> Following: {{ $stats['following_count'] }}</li>
                            <li><i class="ion-ios-film"></i> Movies Watched: {{ $stats['movies_watched'] }}</li>
                            <li><i class="ion-compose"></i> Reviews: {{ $stats['reviews_count'] }}</li>
                        </ul>
                    </div>
                    
                    @if($user->social_links)
                        <div class="user-fav">
                            <p>Social Links</p>
                            <div style="text-align: center; padding: 10px;">
                                @foreach($user->social_links as $platform => $url)
                                    <a href="{{ $url }}" target="_blank" style="display: inline-block; margin: 5px; color: #dcf836; font-size: 24px; transition: color 0.3s;">
                                        <i class="ion-social-{{ strtolower($platform) }}"></i>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <div class="user-fav" style="border-top: 1px solid #405266; padding-top: 20px; margin-top: 20px;">
                        <a href="{{ route('community') }}" class="redbtn" style="display: block; text-align: center; padding: 12px; background: #eb70ac; color: #fff; border-radius: 5px; text-decoration: none; transition: all 0.3s;">
                            <i class="ion-arrow-left-c"></i> Back to Community
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-sm-12 col-xs-12">
                @if($error)
                    <div class="alert alert-danger" style="background: #eb70ac; color: #fff; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #eb70ac;">
                        {{ $error }}
                    </div>
                @endif
                
                <div class="profile-content-wrapper">
                    <!-- Navigation Tabs -->
                    <ul class="profile-tabs-nav">
                        <li class="active"><a href="#favorite-movies" data-toggle="tab"><i class="ion-heart"></i> Favorite Movies</a></li>
                        <li><a href="#recent-activity" data-toggle="tab"><i class="ion-ios-pulse"></i> Recent Activity</a></li>
                        <li><a href="#reviews" data-toggle="tab"><i class="ion-compose"></i> Reviews</a></li>
                    </ul>
                    
                    <div class="tab-content"
                        <!-- Favorite Movies Tab -->
                        <div class="tab-pane active" id="favorite-movies">
                            <div class="tab-section">
                                <h3 class="section-title">
                                    <i class="ion-heart"></i> Favorite Movies 
                                    <span class="count-badge">{{ $favoriteMovies->total() }}</span>
                                </h3>
                                
                                @if($favoriteMovies->count() > 0)
                                    <div class="movies-grid">
                                        @foreach($favoriteMovies as $movie)
                                            <div class="movie-item-card">
                                                <a href="{{ route('movies.show', $movie->movie_id) }}" class="movie-poster-link">
                                                    <div class="movie-poster-container">
                                                        @if($movie->movie_poster)
                                                            <img src="{{ $movie->poster_url }}" alt="{{ $movie->movie_title }}" loading="lazy">
                                                        @else
                                                            <div class="movie-poster-placeholder">
                                                                {{ substr($movie->movie_title, 0, 2) }}
                                                            </div>
                                                        @endif
                                                        
                                                        @if($movie->user_rating)
                                                            <div class="rating-badge">
                                                                <i class="ion-star"></i> {{ number_format($movie->user_rating, 1) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </a>
                                                
                                                <div class="movie-details">
                                                    <h4 class="movie-title">
                                                        <a href="{{ route('movies.show', $movie->movie_id) }}">{{ $movie->movie_title }}</a>
                                                    </h4>
                                                    
                                                    @if($movie->user_review)
                                                        <p class="movie-review">{{ Str::limit($movie->user_review, 120) }}</p>
                                                    @endif
                                                    
                                                    @if($movie->watched_at)
                                                        <p class="movie-date">
                                                            <i class="ion-calendar"></i> 
                                                            {{ $movie->watched_at->format('M d, Y') }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    @if($favoriteMovies->hasPages())
                                        <div class="pagination-container">
                                            {{ $favoriteMovies->links() }}
                                        </div>
                                    @endif
                                @else
                                    <div class="no-content-message">
                                        <i class="ion-ios-film-outline"></i>
                                        <p>{{ $user->name }} hasn't added any favorite movies yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Recent Activity Tab -->
                        <div class="tab-pane" id="recent-activity">
                            <div class="tab-section">
                                <h3 class="section-title">
                                    <i class="ion-ios-pulse"></i> Recent Activity
                                </h3>
                                
                                @if($recentActivities->count() > 0)
                                    <div class="activity-list">
                                        @foreach($recentActivities as $activity)
                                            <div class="activity-card">
                                                <div class="activity-icon-wrapper">
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
                                                        @case('login')
                                                            <i class="ion-log-in"></i>
                                                            @break
                                                        @case('profile_update')
                                                            <i class="ion-edit"></i>
                                                            @break
                                                        @default
                                                            <i class="ion-ios-pulse"></i>
                                                    @endswitch
                                                </div>
                                                
                                                <div class="activity-info">
                                                    <div class="activity-text">
                                                        @switch($activity->activity_type)
                                                            @case('favorite')
                                                                <strong>Added a movie to favorites</strong>
                                                                @break
                                                            @case('review')
                                                                <strong>Wrote a movie review</strong>
                                                                @break
                                                            @case('follow')
                                                                <strong>Started following someone</strong>
                                                                @break
                                                            @case('movie_watched')
                                                                <strong>Watched a movie</strong>
                                                                @break
                                                            @case('login')
                                                                <strong>Logged in</strong>
                                                                @break
                                                            @case('profile_update')
                                                                <strong>Updated profile</strong>
                                                                @break
                                                            @default
                                                                <strong>{{ ucfirst(str_replace('_', ' ', $activity->activity_type)) }}</strong>
                                                        @endswitch
                                                        
                                                        @if($activity->activity_data && isset($activity->activity_data['movie_title']))
                                                            <span class="activity-movie">{{ $activity->activity_data['movie_title'] }}</span>
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="activity-meta">
                                                        <span class="activity-time">{{ $activity->created_at->diffForHumans() }}</span>
                                                        <span class="activity-points"><i class="ion-trophy"></i> +{{ $activity->points }} pts</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="no-content-message">
                                        <i class="ion-ios-pulse-outline"></i>
                                        <p>No recent activity to show.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Reviews Tab -->
                        <div class="tab-pane" id="reviews">
                            <div class="tab-section">
                                <h3 class="section-title">
                                    <i class="ion-compose"></i> Movie Reviews
                                </h3>
                                
                                @php
                                    $reviewedMovies = $favoriteMovies->filter(function($movie) {
                                        return !empty($movie->user_review);
                                    });
                                @endphp
                                
                                @if($reviewedMovies->count() > 0)
                                    <div class="reviews-container">
                                        @foreach($reviewedMovies as $movie)
                                            <div class="review-item">
                                                <div class="review-header">
                                                    <div class="review-poster-wrapper">
                                                        @if($movie->movie_poster)
                                                            <a href="{{ route('movies.show', $movie->movie_id) }}">
                                                                <img src="{{ $movie->poster_url }}" alt="{{ $movie->movie_title }}" class="review-poster-img">
                                                            </a>
                                                        @else
                                                            <div class="review-poster-placeholder">
                                                                {{ substr($movie->movie_title, 0, 2) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="review-movie-details">
                                                        <h4 class="review-movie-title">
                                                            <a href="{{ route('movies.show', $movie->movie_id) }}">{{ $movie->movie_title }}</a>
                                                        </h4>
                                                        @if($movie->user_rating)
                                                            <div class="review-stars">
                                                                @for($i = 1; $i <= 10; $i++)
                                                                    @if($i <= $movie->user_rating)
                                                                        <i class="ion-star" style="color: #dcf836;"></i>
                                                                    @else
                                                                        <i class="ion-star-outline" style="color: #405266;"></i>
                                                                    @endif
                                                                @endfor
                                                                <span class="rating-value">{{ number_format($movie->user_rating, 1) }}/10</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="review-body">
                                                    <p class="review-text">{{ $movie->user_review }}</p>
                                                    
                                                    <div class="review-footer">
                                                        @if($movie->watched_at)
                                                            <span class="review-date">
                                                                <i class="ion-calendar"></i> Watched {{ $movie->watched_at->format('M d, Y') }}
                                                            </span>
                                                        @endif
                                                        <span class="review-added">
                                                            <i class="ion-clock"></i> Added {{ $movie->created_at->diffForHumans() }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="no-content-message">
                                        <i class="ion-compose-outline"></i>
                                        <p>{{ $user->name }} hasn't written any movie reviews yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Sidebar User Information */
.user-information {
    background: #0b1a2a !important;
    padding: 0 !important;
    border-radius: 5px !important;
    border: 1px solid #405266 !important;
    margin-bottom: 30px;
}

.user-img {
    text-align: center !important;
    padding: 30px 20px !important;
    background: linear-gradient(135deg, #0b1a2a, #020d18);
    border-radius: 5px 5px 0 0;
    position: relative;
}

.user-img img {
    width: 120px !important;
    height: 120px !important;
    border-radius: 50% !important;
    margin-bottom: 15px !important;
    object-fit: cover !important;
    border: 4px solid #dcf836 !important;
    box-shadow: 0 4px 15px rgba(220, 248, 54, 0.3);
}

.user-img h3 {
    font-size: 20px !important;
    margin: 10px 0 5px !important;
    font-weight: 700 !important;
}

.user-img p {
    font-size: 14px !important;
    margin: 0 !important;
}

.user-fav {
    margin: 0 !important;
    border-top: 1px solid #405266 !important;
    padding: 0 !important;
}

.user-fav p:first-child {
    color: #3e9fd8 !important;
    font-weight: 600 !important;
    font-size: 14px !important;
    text-align: left !important;
    margin: 0 !important;
    padding: 15px 20px !important;
    background: rgba(62, 159, 216, 0.1);
    text-transform: uppercase !important;
    letter-spacing: 1px;
}

.user-fav ul {
    list-style: none !important;
    padding: 15px 20px !important;
    margin: 0 !important;
}

.user-fav ul li {
    margin-bottom: 12px !important;
    color: #abb7c4 !important;
    padding: 8px 0 !important;
    border-bottom: 1px solid rgba(64, 82, 102, 0.3) !important;
    font-size: 13px !important;
    display: flex;
    align-items: center;
    gap: 10px;
}

.user-fav ul li:last-child {
    border-bottom: none !important;
}

.user-fav ul li i {
    color: #dcf836 !important;
    font-size: 16px !important;
    width: 20px;
}

.user-fav .redbtn {
    background: #eb70ac !important;
    color: #fff !important;
    border: none !important;
    transition: all 0.3s ease !important;
}

.user-fav .redbtn:hover {
    background: #d55a98 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(235, 112, 172, 0.4);
}

/* Main Content Wrapper */
.profile-content-wrapper {
    background: #0b1a2a;
    border-radius: 5px;
    padding: 0;
    border: 1px solid #405266;
}

/* Tabs Navigation */
.profile-tabs-nav {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    background: #020d18;
    border-radius: 5px 5px 0 0;
    border-bottom: 2px solid #405266;
}

.profile-tabs-nav li {
    flex: 1;
    text-align: center;
}

.profile-tabs-nav li a {
    display: block;
    padding: 18px 20px;
    color: #abb7c4;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    border-bottom: 3px solid transparent;
    position: relative;
}

.profile-tabs-nav li a i {
    margin-right: 8px;
    font-size: 16px;
}

.profile-tabs-nav li a:hover {
    color: #dcf836;
    background: rgba(220, 248, 54, 0.05);
}

.profile-tabs-nav li.active a {
    color: #dcf836;
    border-bottom-color: #dcf836;
    background: rgba(220, 248, 54, 0.1);
}

/* Tab Content */
.tab-content {
    padding: 0;
}

.tab-section {
    padding: 30px;
}

.section-title {
    color: #ffffff;
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #405266;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-title i {
    color: #dcf836;
    font-size: 24px;
}

.count-badge {
    background: #eb70ac;
    color: #fff;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 14px;
    margin-left: auto;
}

/* Movies Grid */
.movies-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 25px;
    margin-top: 20px;
}

.movie-item-card {
    background: #020d18;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #405266;
    transition: all 0.3s ease;
}

.movie-item-card:hover {
    transform: translateY(-5px);
    border-color: #dcf836;
    box-shadow: 0 8px 20px rgba(220, 248, 54, 0.2);
}

.movie-poster-link {
    display: block;
    position: relative;
}

.movie-poster-container {
    position: relative;
    width: 100%;
    padding-bottom: 150%;
    overflow: hidden;
    background: #020d18;
}

.movie-poster-container img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.movie-item-card:hover .movie-poster-container img {
    transform: scale(1.05);
}

.movie-poster-placeholder {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #eb70ac, #dd003f);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    font-weight: bold;
    text-transform: uppercase;
}

.rating-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(2, 13, 24, 0.95);
    color: #dcf836;
    padding: 6px 12px;
    border-radius: 5px;
    font-size: 13px;
    font-weight: bold;
    display: flex;
    align-items: center;
    gap: 4px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
}

.rating-badge i {
    font-size: 14px;
}

.movie-details {
    padding: 15px;
}

.movie-title {
    margin: 0 0 10px 0;
    font-size: 15px;
    font-weight: 600;
}

.movie-title a {
    color: #ffffff;
    text-decoration: none;
    transition: color 0.3s ease;
}

.movie-title a:hover {
    color: #dcf836;
}

.movie-review {
    font-size: 12px;
    color: #abb7c4;
    line-height: 1.5;
    margin: 8px 0;
    font-style: italic;
}

.movie-date {
    font-size: 11px;
    color: #7a8a9e;
    margin: 8px 0 0 0;
    display: flex;
    align-items: center;
    gap: 5px;
}

.movie-date i {
    color: #3e9fd8;
}
}

/* Activity List */
.activity-list {
    margin-top: 20px;
}

.activity-card {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 20px;
    background: #020d18;
    border: 1px solid #405266;
    border-radius: 8px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}

.activity-card:hover {
    border-color: #3e9fd8;
    background: rgba(62, 159, 216, 0.05);
}

.activity-icon-wrapper {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #eb70ac, #dd003f);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
    box-shadow: 0 4px 10px rgba(235, 112, 172, 0.3);
}

.activity-info {
    flex: 1;
}

.activity-text {
    margin-bottom: 8px;
    color: #ffffff;
    font-size: 14px;
}

.activity-text strong {
    color: #dcf836;
    font-weight: 600;
}

.activity-movie {
    display: block;
    color: #abb7c4;
    margin-top: 5px;
    font-style: italic;
}

.activity-meta {
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 12px;
}

.activity-time {
    color: #7a8a9e;
}

.activity-points {
    color: #dcf836;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.activity-points i {
    color: #eb70ac;
}

/* Reviews Container */
.reviews-container {
    margin-top: 20px;
}

.review-item {
    background: #020d18;
    border: 1px solid #405266;
    border-radius: 8px;
    padding: 25px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.review-item:hover {
    border-color: #dcf836;
    box-shadow: 0 4px 15px rgba(220, 248, 54, 0.1);
}

.review-header {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #405266;
}

.review-poster-wrapper {
    flex-shrink: 0;
}

.review-poster-img {
    width: 80px;
    height: 120px;
    border-radius: 5px;
    object-fit: cover;
    border: 1px solid #405266;
    transition: transform 0.3s ease;
}

.review-poster-img:hover {
    transform: scale(1.05);
}

.review-poster-placeholder {
    width: 80px;
    height: 120px;
    background: linear-gradient(135deg, #eb70ac, #dd003f);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: bold;
    border-radius: 5px;
}

.review-movie-details {
    flex: 1;
}

.review-movie-title {
    margin: 0 0 10px 0;
    font-size: 18px;
    font-weight: 700;
}

.review-movie-title a {
    color: #ffffff;
    text-decoration: none;
    transition: color 0.3s ease;
}

.review-movie-title a:hover {
    color: #dcf836;
}

.review-stars {
    display: flex;
    align-items: center;
    gap: 3px;
    margin-top: 8px;
}

.review-stars i {
    font-size: 14px;
}

.rating-value {
    margin-left: 10px;
    color: #dcf836;
    font-weight: 600;
    font-size: 14px;
}

.review-body {
    color: #abb7c4;
}

.review-text {
    font-size: 14px;
    line-height: 1.8;
    margin-bottom: 15px;
    color: #abb7c4;
}

.review-footer {
    display: flex;
    align-items: center;
    gap: 20px;
    font-size: 12px;
    color: #7a8a9e;
    padding-top: 15px;
    border-top: 1px solid rgba(64, 82, 102, 0.3);
}

.review-date,
.review-added {
    display: flex;
    align-items: center;
    gap: 5px;
}

.review-footer i {
    color: #3e9fd8;
}

/* No Content Message */
.no-content-message {
    text-align: center;
    padding: 60px 20px;
    color: #7a8a9e;
}

.no-content-message i {
    font-size: 64px;
    color: #405266;
    margin-bottom: 20px;
    display: block;
}

.no-content-message p {
    font-size: 16px;
    margin: 0;
}

/* Pagination */
.pagination-container {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #405266;
}

/* Responsive Design */
@media (max-width: 991px) {
    .movies-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
    }
}

@media (max-width: 767px) {
    .user-information {
        margin-bottom: 20px;
    }
    
    .profile-tabs-nav {
        flex-direction: column;
    }
    
    .profile-tabs-nav li {
        border-bottom: 1px solid #405266;
    }
    
    .profile-tabs-nav li:last-child {
        border-bottom: none;
    }
    
    .tab-section {
        padding: 20px 15px;
    }
    
    .section-title {
        font-size: 18px;
        flex-wrap: wrap;
    }
    
    .movies-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .review-header {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .activity-card {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .activity-meta {
        flex-direction: column;
        gap: 8px;
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