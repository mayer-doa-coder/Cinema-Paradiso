@extends('layouts.app')

@section('title', 'Community - Cinema Paradiso')

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
                            <li>
                                <a href="{{ route('user.profile') }}" style="color: #e9d736; font-weight: 500;">
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

<div class="hero mv-single-hero">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Community header -->
                <h1 class="page-title">Cinema Community</h1>
                <p class="page-subtitle">Discover movie lovers from around the world</p>
            </div>
        </div>
    </div>
</div>

<div class="community-header-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="community-stats-bar">
                    <div class="stat-item">
                        <span class="stat-number">{{ number_format($totalUsers) }}</span>
                        <span class="stat-label">Members</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ number_format($activeUsers) }}</span>
                        <span class="stat-label">Active</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ number_format($totalMoviesWatched) }}</span>
                        <span class="stat-label">Movies Watched</span>
                    </div>
                    <div class="sort-container">
                        <label for="sort-users">Sort by:</label>
                        <select id="sort-users" class="form-control">
                            <option value="popularity" {{ $currentSort == 'popularity' ? 'selected' : '' }}>Most Popular</option>
                            <option value="recent" {{ $currentSort == 'recent' ? 'selected' : '' }}>Recently Active</option>
                            <option value="alphabetical" {{ $currentSort == 'alphabetical' ? 'selected' : '' }}>Alphabetical</option>
                        </select>
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
                
                <!-- Users Grid -->
                <div class="community-users-grid">
                    @forelse($users as $user)
                        <div class="user-card">
                            <a href="{{ route('community.profile', $user->username) }}" class="user-card-link">
                                <div class="user-card-header">
                                    <div class="user-avatar-small">
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                                        @if($user->last_active && $user->last_active->gt(now()->subHours(24)))
                                            <span class="online-dot"></span>
                                        @endif
                                    </div>
                                    <div class="user-basic-info">
                                        <h4 class="user-name">{{ $user->name }}</h4>
                                        <p class="user-username">{{ $user->username }}</p>
                                    </div>
                                </div>
                                
                                <div class="user-stats-compact">
                                    <div class="stat-compact">
                                        <i class="ion-ios-star"></i>
                                        <span>{{ number_format($user->popularity_score) }}</span>
                                    </div>
                                    <div class="stat-compact">
                                        <i class="ion-ios-people"></i>
                                        <span>{{ $user->followers()->count() }}</span>
                                    </div>
                                    <div class="stat-compact">
                                        <i class="ion-ios-film"></i>
                                        <span>{{ $user->total_movies_watched }}</span>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- Favorite Movies Preview -->
                            @if($user->favoriteMovies->count() >= 5)
                                <div class="user-favorite-movies-compact">
                                    <div class="favorite-movies-list-compact">
                                        @foreach($user->favoriteMovies->take(5) as $movie)
                                            @if($movie->poster_url)
                                                <a href="{{ route('movies.show', $movie->movie_id) }}" 
                                                   class="favorite-movie-item-compact" 
                                                   title="{{ $movie->movie_title }}"
                                                   onclick="event.stopPropagation();">
                                                    <img src="{{ $movie->poster_url }}" 
                                                         alt="{{ $movie->movie_title }}"
                                                         width="35"
                                                         height="52"
                                                         loading="lazy"
                                                         onerror="this.src='{{ asset('images/uploads/poster-placeholder.jpg') }}'; this.onerror=null;">
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="no-users-found">
                            <h3>No community members found</h3>
                            <p>Be the first to join our movie-loving community!</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- Pagination -->
                @if($users->hasPages())
                    <div class="community-pagination">
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                @endif
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
                    
                    <!-- Community Guidelines -->
                    <div class="community-guidelines">
                        <h4 class="sb-title">Community Guidelines</h4>
                        <ul>
                            <li>Be respectful and kind to fellow movie lovers</li>
                            <li>Share genuine movie reviews and ratings</li>
                            <li>Keep discussions relevant to movies and cinema</li>
                            <li>No spam or promotional content</li>
                            <li>Help others discover great films</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.page-title {
    font-size: 3em;
    margin-bottom: 10px;
    color: #ffffff;
    text-align: center;
    font-weight: bold;
}

.page-subtitle {
    font-size: 1.2em;
    color: #abb7c4;
    text-align: center;
    margin-bottom: 30px;
}

.community-header-section {
    background-color: #020d18;
    padding: 20px 0 30px;
}

.community-stats-bar {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 50px;
    flex-wrap: wrap;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 2em;
    font-weight: bold;
    color: #e9d736;
}

.stat-label {
    display: block;
    font-size: 0.9em;
    color: #abb7c4;
    text-transform: uppercase;
    margin-top: 5px;
}

.sort-container {
    display: flex;
    align-items: center;
    gap: 10px;
}

.sort-container label {
    color: #abb7c4;
    margin: 0;
    white-space: nowrap;
}

.sort-container .form-control {
    width: 180px;
    background-color: #0b1a2a;
    border: 1px solid #405266;
    color: #ffffff;
    padding: 8px 12px;
    border-radius: 3px;
}

.sort-container .form-control:focus {
    border-color: #e9d736;
    outline: none;
}

.community-users-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

.user-card {
    border-radius: 8px;
    border: 1px solid #235fa2ff;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.user-card:hover {
    transform: translateY(-3px);
    border-color: #e9d736;
    box-shadow: 0 4px 12px rgba(220, 248, 54, 0.1);
}

.user-card-link {
    display: block;
    padding: 15px;
    text-decoration: none;
    color: inherit;
}

.user-card-link:hover,
.user-card-link:focus {
    text-decoration: none;
    color: inherit;
}

.user-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.user-avatar-small {
    position: relative;
    flex-shrink: 0;
}

.user-avatar-small img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.online-dot {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 10px;
    height: 10px;
    background: #4caf50;
    border-radius: 50%;
    border: 2px solid #0b1a2a;
}

.user-basic-info {
    flex: 1;
    min-width: 0;
}

.user-name {
    margin: 0;
    font-size: 1em;
    line-height: 1.2;
    color: #ffffff;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-card-link:hover .user-name {
    color: #e9d736;
}

.user-username {
    color: #abb7c4;
    margin: 2px 0 0 0;
    font-size: 0.8em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-stats-compact {
    display: flex;
    justify-content: space-around;
    padding: 10px 0;
    background: #020d18;
    border-radius: 5px;
}

.stat-compact {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #e9d736;
    font-size: 0.85em;
    font-weight: 600;
}

.stat-compact i {
    font-size: 1.1em;
}

.stat-compact span {
    color: #ffffff;
}

.user-favorite-movies-compact {
    margin: 12px 15px;
}

.favorite-movies-list-compact {
    display: flex;
    gap: 6px;
    justify-content: center;
    flex-wrap: wrap;
}

.favorite-movie-item-compact {
    width: 35px;
    height: 52px;
    border-radius: 3px;
    overflow: hidden;
    border: 1px solid #405266;
    transition: all 0.2s ease;
    cursor: pointer;
    display: block;
    position: relative;
    flex-shrink: 0;
}

.favorite-movie-item-compact:hover {
    transform: scale(1.15);
    border-color: #e9d736;
    box-shadow: 0 2px 8px rgba(220, 248, 54, 0.3);
    z-index: 10;
}

.favorite-movie-item-compact img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
}

.no-users-found {
    text-align: center;
    padding: 60px 20px;
    color: #abb7c4;
}

.no-users-found h3 {
    color: #ffffff;
    margin-bottom: 10px;
}

.community-pagination {
    margin-top: 40px;
    text-align: center;
}

.community-guidelines {
    margin-top: 30px;
    background: #0b1a2a;
    padding: 20px;
    border-radius: 5px;
    border: 1px solid #405266;
}

.community-guidelines h4 {
    color: #e9d736;
    margin-bottom: 15px;
}

.community-guidelines ul {
    padding-left: 20px;
    color: #abb7c4;
}

.community-guidelines li {
    margin-bottom: 8px;
    font-size: 0.9em;
}

@media (max-width: 768px) {
    .community-stats-bar {
        gap: 20px;
    }
    
    .stat-number {
        font-size: 1.5em;
    }
    
    .community-users-grid {
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 15px;
    }
    
    .sort-container {
        width: 100%;
        justify-content: center;
    }
    
    .sort-container .form-control {
        width: 100%;
    }
    
    .user-card-link {
        padding: 12px;
    }
    
    .user-avatar-small img {
        width: 45px;
        height: 45px;
    }
    
    .favorite-movie-item-compact {
        width: 30px;
        height: 45px;
    }
}

@media (min-width: 769px) and (max-width: 1024px) {
    .community-users-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }
}

@media (min-width: 1400px) {
    .community-users-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    }
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sortSelect = document.getElementById('sort-users');
    
    // Sort functionality
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const url = new URL(window.location);
            url.searchParams.set('sort', this.value);
            window.location.href = url.toString();
        });
    }
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