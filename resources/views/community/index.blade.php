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
        
        @include('partials._search')
    </div>
</header>
<!-- END | Header -->

<div class="hero mv-single-hero">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Community header with search -->
                <div class="community-header">
                    <h1 class="community-title">Cinema Community</h1>
                    <p class="community-subtitle">Discover movie lovers from around the world</p>
                    
                    <div class="community-stats">
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
                    </div>
                    
                    <!-- Search and sort controls -->
                    <div class="community-controls">
                        <div class="search-container">
                            <input type="text" id="user-search" placeholder="Search members..." class="form-control">
                            <div id="search-results" class="search-dropdown"></div>
                        </div>
                        
                        <div class="sort-container">
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
                            <div class="user-avatar-container">
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="user-avatar-circle">
                                @if($user->last_active && $user->last_active->gt(now()->subHours(24)))
                                    <span class="online-indicator"></span>
                                @endif
                            </div>
                            
                            <div class="user-info">
                                <h3 class="user-name">
                                    <a href="{{ route('community.profile', $user->username) }}">{{ $user->name }}</a>
                                </h3>
                                <p class="user-username">@{{ $user->username }}</p>
                                
                                @if($user->location)
                                    <p class="user-location">
                                        <i class="ion-location"></i> {{ $user->location }}
                                    </p>
                                @endif
                                
                                @if($user->platform)
                                    <p class="user-platform">
                                        <i class="ion-link"></i> {{ ucfirst($user->platform) }}
                                        @if($user->platform_username)
                                            ({{ $user->platform_username }})
                                        @endif
                                    </p>
                                @endif
                                
                                <div class="user-stats">
                                    <div class="stat">
                                        <span class="stat-value">{{ $user->popularity_score }}</span>
                                        <span class="stat-name">Points</span>
                                    </div>
                                    <div class="stat">
                                        <span class="stat-value">{{ $user->followers()->count() }}</span>
                                        <span class="stat-name">Followers</span>
                                    </div>
                                    <div class="stat">
                                        <span class="stat-value">{{ $user->total_movies_watched }}</span>
                                        <span class="stat-name">Movies</span>
                                    </div>
                                </div>
                                
                                @if($user->bio)
                                    <p class="user-bio">{{ Str::limit($user->bio, 100) }}</p>
                                @endif
                                
                                <!-- Favorite Movies Preview -->
                                @if($user->favoriteMovies->count() > 0)
                                    <div class="user-favorite-movies">
                                        <h5>Favorite Movies</h5>
                                        <div class="favorite-movies-list">
                                            @foreach($user->favoriteMovies->take(4) as $movie)
                                                <div class="favorite-movie-item">
                                                    @if($movie->movie_poster)
                                                        <img src="{{ $movie->poster_url }}" alt="{{ $movie->movie_title }}" title="{{ $movie->movie_title }}">
                                                    @else
                                                        <div class="movie-placeholder">{{ substr($movie->movie_title, 0, 2) }}</div>
                                                    @endif
                                                </div>
                                            @endforeach
                                            @if($user->favoriteMovies->count() > 4)
                                                <div class="more-movies">+{{ $user->favoriteMovies->count() - 4 }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="user-actions">
                                    <a href="{{ route('community.profile', $user->username) }}" class="btn btn-primary btn-sm">View Profile</a>
                                </div>
                            </div>
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
.community-header {
    text-align: center;
    padding: 40px 0;
    color: white;
}

.community-title {
    font-size: 3em;
    margin-bottom: 10px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
}

.community-subtitle {
    font-size: 1.2em;
    opacity: 0.9;
    margin-bottom: 30px;
}

.community-stats {
    display: flex;
    justify-content: center;
    gap: 40px;
    margin-bottom: 30px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 2em;
    font-weight: bold;
    color: #dd2c00;
}

.stat-label {
    display: block;
    font-size: 0.9em;
    opacity: 0.8;
}

.community-controls {
    display: flex;
    justify-content: center;
    gap: 20px;
    max-width: 600px;
    margin: 0 auto;
}

.search-container {
    position: relative;
    flex: 1;
}

.search-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-top: none;
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
}

.search-dropdown .search-result {
    padding: 10px;
    border-bottom: 1px solid #eee;
    cursor: pointer;
}

.search-dropdown .search-result:hover {
    background: #f5f5f5;
}

.sort-container {
    width: 200px;
}

.community-users-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
    margin-top: 30px;
}

.user-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
}

.user-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.user-avatar-container {
    position: relative;
    text-align: center;
    margin-bottom: 15px;
}

.user-avatar-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 3px solid #dd2c00;
    object-fit: cover;
}

.online-indicator {
    position: absolute;
    bottom: 5px;
    right: calc(50% - 35px);
    width: 15px;
    height: 15px;
    background: #4caf50;
    border-radius: 50%;
    border: 2px solid white;
}

.user-info {
    text-align: center;
}

.user-name a {
    color: #333;
    text-decoration: none;
    font-weight: bold;
    font-size: 1.2em;
}

.user-name a:hover {
    color: #dd2c00;
}

.user-username {
    color: #666;
    margin: 5px 0;
    font-size: 0.9em;
}

.user-location, .user-platform {
    color: #888;
    font-size: 0.8em;
    margin: 3px 0;
}

.user-stats {
    display: flex;
    justify-content: space-around;
    margin: 15px 0;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 5px;
}

.stat {
    text-align: center;
}

.stat-value {
    display: block;
    font-weight: bold;
    color: #dd2c00;
    font-size: 1.1em;
}

.stat-name {
    font-size: 0.8em;
    color: #666;
}

.user-bio {
    font-size: 0.9em;
    color: #666;
    margin: 10px 0;
    font-style: italic;
}

.user-favorite-movies {
    margin: 15px 0;
}

.user-favorite-movies h5 {
    font-size: 0.9em;
    margin-bottom: 10px;
    color: #333;
}

.favorite-movies-list {
    display: flex;
    justify-content: center;
    gap: 5px;
    align-items: center;
}

.favorite-movie-item img {
    width: 30px;
    height: 45px;
    border-radius: 3px;
    object-fit: cover;
}

.movie-placeholder {
    width: 30px;
    height: 45px;
    background: #dd2c00;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7em;
    font-weight: bold;
    border-radius: 3px;
}

.more-movies {
    width: 30px;
    height: 45px;
    background: #f0f0f0;
    color: #666;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7em;
    border-radius: 3px;
}

.user-actions {
    margin-top: 15px;
}

.btn-sm {
    padding: 5px 15px;
    font-size: 0.8em;
}

.no-users-found {
    text-align: center;
    padding: 60px 20px;
    color: #666;
}

.community-pagination {
    margin-top: 40px;
    text-align: center;
}

.community-guidelines {
    margin-top: 30px;
}

.community-guidelines ul {
    padding-left: 20px;
    color: #666;
}

.community-guidelines li {
    margin-bottom: 8px;
    font-size: 0.9em;
}

@media (max-width: 768px) {
    .community-controls {
        flex-direction: column;
        gap: 10px;
    }
    
    .community-users-grid {
        grid-template-columns: 1fr;
    }
    
    .community-stats {
        gap: 20px;
    }
    
    .stat-number {
        font-size: 1.5em;
    }
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('user-search');
    const searchResults = document.getElementById('search-results');
    const sortSelect = document.getElementById('sort-users');
    
    // Search functionality
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }
        
        searchTimeout = setTimeout(() => {
            fetch(`/community/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.users && data.users.length > 0) {
                        let html = '';
                        data.users.forEach(user => {
                            html += `
                                <div class="search-result" onclick="window.location.href='${user.profile_url}'">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <img src="${user.avatar_url}" alt="${user.name}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                        <div>
                                            <div style="font-weight: bold;">${user.name}</div>
                                            <div style="font-size: 0.8em; color: #666;">@${user.username}</div>
                                            ${user.location ? `<div style="font-size: 0.7em; color: #888;"><i class="ion-location"></i> ${user.location}</div>` : ''}
                                        </div>
                                        <div style="margin-left: auto; color: #dd2c00; font-weight: bold;">${user.popularity_score}</div>
                                    </div>
                                </div>
                            `;
                        });
                        searchResults.innerHTML = html;
                        searchResults.style.display = 'block';
                    } else {
                        searchResults.innerHTML = '<div class="search-result">No users found</div>';
                        searchResults.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    searchResults.style.display = 'none';
                });
        }, 300);
    });
    
    // Hide search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
    
    // Sort functionality
    sortSelect.addEventListener('change', function() {
        const url = new URL(window.location);
        url.searchParams.set('sort', this.value);
        window.location.href = url.toString();
    });
});

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