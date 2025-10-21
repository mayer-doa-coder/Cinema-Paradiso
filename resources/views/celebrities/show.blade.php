@extends('layouts.app')

@section('title', $celebrity['name'] . ' - Celebrity Profile')

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
                                <i class="ion-link"></i> OFFICIAL SITE
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
                                <i class="ion-ios-arrow-left"></i> BACK TO CELEBRITIES
                            </a>
                        </div>
                    </div>

                    @if($celebrity['biography'])
                        <div class="celebrity-section">
                            <h3>Biography</h3>
                            <div class="biography-content">
                                <p id="biographyText" class="biography-text collapsed">{{ $celebrity['biography'] }}</p>
                                <a href="#" id="readMoreBtn" class="read-more-btn" onclick="toggleBiography(event)">Read More</a>
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
                                            <a href="{{ route('movies.show', $movie['id']) }}" class="movie-poster-link">
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
                                            </a>
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
    background: #020d18;
    min-height: 600px;
    padding: 40px 0;
}

.breadcrumb-nav {
    color: #999;
    margin-bottom: 20px;
}

.breadcrumb-nav a {
    color: #e9d736;
    text-decoration: none;
}

.breadcrumb-nav span {
    margin: 0 10px;
    color: #666;
}

.celebrity-profile-sidebar {
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
    color: #e9d736;
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

.biography-text.collapsed {
    display: -webkit-box;
    -webkit-line-clamp: 6;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

.biography-text.expanded {
    display: block;
}

.read-more-btn {
    color: #e9d736;
    text-decoration: none;
    font-weight: 500;
    margin-top: 10px;
    display: inline-block;
    cursor: pointer;
    transition: color 0.3s ease;
}

.read-more-btn:hover {
    color: #e9d736;
    text-decoration: none;
}

.movie-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 20px;
}

.movie-item-small {
    position: relative;
    background: #1a1a1a;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

.movie-item-small:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 20px rgba(233, 215, 54, 0.3);
}

.movie-poster-link {
    display: block;
    text-decoration: none;
    color: inherit;
    position: relative;
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
    transition: transform 0.3s ease;
}

.movie-item-small:hover .movie-poster-img {
    transform: scale(1.1);
}

.movie-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.8) 50%, transparent 100%);
    color: white;
    padding: 20px 15px 15px;
    transform: translateY(65%);
    transition: transform 0.3s ease;
}

.movie-item-small:hover .movie-overlay {
    transform: translateY(0);
}

.movie-info h5 {
    margin: 0 0 8px;
    font-size: 14px;
    font-weight: bold;
    color: #fff;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

.movie-info .character {
    color: #e9d736;
    font-size: 12px;
    margin: 0 0 5px;
    font-style: italic;
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
    font-size: 13px;
    font-weight: bold;
}

.movie-info .rating i {
    color: #f39c12;
    font-size: 14px;
}

.btn {
    display: inline-block;
    padding: 5px 5px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: #ec6eab;
    color: #ffffff;
    font-weight: bold;
}

.btn-primary:hover {
    background: #ec6eab;
    transform: translateY(-2px);
}

.btn-secondary {
    background: #ec6eab;
    color: #fff;
    border: 1px solid #444;
}

.btn-secondary:hover {
    background: #ec6eab3;
    border-color: #e9d736;
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
// Biography Read More/Less Toggle
function toggleBiography(event) {
    event.preventDefault();
    const bioText = document.getElementById('biographyText');
    const readMoreBtn = document.getElementById('readMoreBtn');
    
    if (bioText.classList.contains('collapsed')) {
        bioText.classList.remove('collapsed');
        bioText.classList.add('expanded');
        readMoreBtn.textContent = 'Read Less';
    } else {
        bioText.classList.remove('expanded');
        bioText.classList.add('collapsed');
        readMoreBtn.textContent = 'Read More';
    }
}

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