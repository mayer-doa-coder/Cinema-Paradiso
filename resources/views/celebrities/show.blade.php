@extends('layouts.app')

@section('title', $celebrity['name'] . ' - Celebrity Profile')

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
                            </a>
                        </li>
                        <li class="first active">
                            <a class="btn btn-default lv1" href="{{ route('celebrities') }}">
                            Celebrities
                            </a>
                        </li>
                        <li class="first">
                            <a class="btn btn-default lv1" href="{{ route('blog') }}">
                            News
                            </a>
                        </li>
                        <li class="first">
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
                    <option value="celebrities">Celebrities</option>
                </select>
            </div>
            <div class="search-input">
                <input type="text" id="search-query" placeholder="Search for a movie, TV Show, or celebrity">
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
                <div class="breadcrumb-nav">
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <a href="{{ route('celebrities') }}">Celebrities</a>
                    <span>/</span>
                    <span>{{ $celebrity['name'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-single celebrity-single">
    <div class="container">
        <div class="row ipad-width">
            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="celebrity-profile-sidebar">
                    <div class="celebrity-avatar-large">
                        <img src="{{ $celebrity['profile_url'] }}" alt="{{ $celebrity['name'] }}" 
                             class="celebrity-profile-image" id="celebrity-main-image">
                    </div>
                    
                    <div class="celebrity-stats">
                        <div class="stat-item">
                            <span class="stat-label">Popularity</span>
                            <span class="stat-value">{{ number_format($celebrity['popularity'], 1) }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Known For</span>
                            <span class="stat-value">{{ $celebrity['known_for_department'] }}</span>
                        </div>
                        @if($celebrity['birthday'])
                            <div class="stat-item">
                                <span class="stat-label">Born</span>
                                <span class="stat-value">{{ \Carbon\Carbon::parse($celebrity['birthday'])->format('M d, Y') }}</span>
                            </div>
                        @endif
                        @if($celebrity['place_of_birth'])
                            <div class="stat-item">
                                <span class="stat-label">Birth Place</span>
                                <span class="stat-value">{{ $celebrity['place_of_birth'] }}</span>
                            </div>
                        @endif
                    </div>

                    @if($celebrity['homepage'])
                        <div class="celebrity-links">
                            <a href="{{ $celebrity['homepage'] }}" target="_blank" class="btn btn-primary">
                                <i class="ion-link"></i> Official Website
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-8 col-sm-12 col-xs-12">
                <div class="celebrity-content">
                    <div class="celebrity-header">
                        <h1 class="celebrity-name">{{ $celebrity['name'] }}</h1>
                        <p class="celebrity-role">{{ $celebrity['known_for_department'] }}</p>
                        
                        <div class="celebrity-actions">
                            <a href="{{ route('celebrities') }}" class="btn btn-secondary">
                                <i class="ion-ios-arrow-left"></i> Back to Celebrities
                            </a>
                        </div>
                    </div>

                    @if($celebrity['biography'])
                        <div class="celebrity-section">
                            <h3>Biography</h3>
                            <div class="biography-content">
                                <p>{{ $celebrity['biography'] }}</p>
                            </div>
                        </div>
                    @endif

                    @if(count($movies) > 0)
                        <div class="celebrity-section">
                            <h3>Known For</h3>
                            <div class="celebrity-movies">
                                <div class="movie-grid">
                                    @foreach($movies as $movie)
                                        <div class="movie-item-small">
                                            <div class="movie-poster">
                                                <img src="{{ $movie['poster_url'] }}" alt="{{ $movie['title'] }}"
                                                     class="movie-poster-img">
                                                <div class="movie-overlay">
                                                    <div class="movie-info">
                                                        <h5>{{ $movie['title'] }}</h5>
                                                        @if($movie['character'])
                                                            <p class="character">as {{ $movie['character'] }}</p>
                                                        @endif
                                                        @if($movie['release_date'])
                                                            <p class="release-year">{{ \Carbon\Carbon::parse($movie['release_date'])->format('Y') }}</p>
                                                        @endif
                                                        @if($movie['vote_average'] > 0)
                                                            <div class="rating">
                                                                <i class="ion-star"></i>
                                                                <span>{{ number_format($movie['vote_average'], 1) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.celebrity-single {
    background: #1e1e1e;
    min-height: 600px;
    padding: 40px 0;
}

.breadcrumb-nav {
    color: #999;
    margin-bottom: 20px;
}

.breadcrumb-nav a {
    color: #dcf836;
    text-decoration: none;
}

.breadcrumb-nav span {
    margin: 0 10px;
    color: #666;
}

.celebrity-profile-sidebar {
    background: #2a2a2a;
    padding: 30px;
    border-radius: 8px;
    text-align: center;
}

.celebrity-avatar-large {
    margin-bottom: 30px;
}

.celebrity-profile-image {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #dcf836;
    transition: all 0.3s ease;
}

.celebrity-profile-image:hover {
    transform: scale(1.05);
}

.celebrity-stats {
    margin-bottom: 30px;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #333;
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-label {
    color: #999;
    font-size: 14px;
}

.stat-value {
    color: #fff;
    font-weight: bold;
}

.celebrity-links .btn {
    width: 100%;
    margin-bottom: 10px;
}

.celebrity-content {
    padding-left: 30px;
}

.celebrity-header {
    margin-bottom: 40px;
    padding-bottom: 20px;
    border-bottom: 2px solid #333;
}

.celebrity-name {
    color: #fff;
    font-size: 2.5em;
    margin-bottom: 10px;
    font-weight: bold;
}

.celebrity-role {
    color: #dcf836;
    font-size: 1.2em;
    margin-bottom: 20px;
}

.celebrity-actions .btn {
    margin-right: 15px;
}

.celebrity-section {
    margin-bottom: 40px;
}

.celebrity-section h3 {
    color: #fff;
    font-size: 1.5em;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #333;
}

.biography-content p {
    color: #ccc;
    line-height: 1.6;
    font-size: 16px;
}

.movie-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 20px;
}

.movie-item-small {
    position: relative;
    background: #333;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.movie-item-small:hover {
    transform: translateY(-5px);
}

.movie-poster {
    position: relative;
    height: 225px;
    overflow: hidden;
}

.movie-poster-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.movie-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.9));
    color: white;
    padding: 15px;
    transform: translateY(100%);
    transition: transform 0.3s ease;
}

.movie-item-small:hover .movie-overlay {
    transform: translateY(0);
}

.movie-info h5 {
    margin: 0 0 5px;
    font-size: 14px;
    font-weight: bold;
}

.movie-info .character {
    color: #dcf836;
    font-size: 12px;
    margin: 0 0 5px;
}

.movie-info .release-year {
    color: #999;
    font-size: 12px;
    margin: 0 0 5px;
}

.movie-info .rating {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
}

.movie-info .rating i {
    color: #f39c12;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: #dcf836;
    color: #000;
}

.btn-primary:hover {
    background: #c9e62f;
    transform: translateY(-2px);
}

.btn-secondary {
    background: #2a2a2a;
    color: #fff;
    border: 1px solid #444;
}

.btn-secondary:hover {
    background: #333;
    border-color: #dcf836;
}

@media (max-width: 768px) {
    .celebrity-content {
        padding-left: 0;
        margin-top: 30px;
    }
    
    .celebrity-name {
        font-size: 2em;
    }
    
    .movie-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 15px;
    }
    
    .movie-poster {
        height: 180px;
    }
    
    .celebrity-profile-image {
        width: 150px;
        height: 150px;
    }
}

@media (max-width: 480px) {
    .movie-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .celebrity-profile-sidebar {
        padding: 20px;
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
    const celebrityImage = document.getElementById('celebrity-main-image');
    const defaultAvatar = '{{ asset('images/uploads/default-avatar.jpg') }}';
    const defaultPoster = '{{ asset('images/uploads/default-movie-poster.jpg') }}';
    
    // Handle celebrity image fallback
    if (celebrityImage) {
        celebrityImage.addEventListener('error', function() {
            this.src = defaultAvatar;
        });
    }
    
    // Handle movie poster image fallbacks
    const movieImages = document.querySelectorAll('.movie-poster-img');
    movieImages.forEach(function(img) {
        img.addEventListener('error', function() {
            this.src = defaultPoster;
        });
    });
    
    function performSearch() {
        const query = searchInput.value.trim();
        const type = searchType.value;
        
        if (query) {
            if (type === 'celebrities') {
                const searchUrl = `{{ route('celebrities') }}?search=${encodeURIComponent(query)}`;
                window.location.href = searchUrl;
            } else {
                const searchUrl = `{{ route('home.search') }}?q=${encodeURIComponent(query)}&type=${type}`;
                window.location.href = searchUrl;
            }
        }
    }
    
    // Search on icon click
    if (searchIcon) {
        searchIcon.addEventListener('click', performSearch);
    }
    
    // Search on Enter key press
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
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

@endsection