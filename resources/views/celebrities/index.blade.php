@extends('layouts.app')

@section('title', 'Celebrities - Cinema Paradiso')

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
                <input type="text" id="search-query" placeholder="Search for a movie, TV Show, or celebrity" value="{{ $searchQuery }}">
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
                <div class="page-header">
                    <h1>Celebrities</h1>
                    <p>Discover your favorite actors, directors, and film industry professionals</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-single">
    <div class="container">
        <div class="row ipad-width2">
            <div class="col-md-9 col-sm-12 col-xs-12">
                @if($error)
                    <div class="alert alert-warning">
                        <i class="ion-alert-circled"></i> {{ $error }}
                    </div>
                @endif

                <div class="topbar-filter">
                    <p>Found <span>{{ number_format($totalResults) }} celebrities</span> in total</p>
                    <div class="filter-controls">
                        <label>Sort by:</label>
                        <select id="sort-select">
                            <option value="popularity" {{ $currentSort == 'popularity' ? 'selected' : '' }}>Popularity Descending</option>
                            <option value="name" {{ $currentSort == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                        </select>
                    </div>
                </div>

                @if($searchQuery)
                    <div class="search-info">
                        <p>Search results for: <strong>"{{ $searchQuery }}"</strong> 
                        <a href="{{ route('celebrities') }}" class="clear-search">Clear search</a></p>
                    </div>
                @endif

                <div class="celebrity-items">
                    @forelse($celebrities as $celebrity)
                        <div class="ceb-item">
                            <a href="{{ route('celebrities.show', $celebrity['id']) }}">
                                <div class="celebrity-avatar-container">
                                    <img src="{{ $celebrity['profile_url'] }}" 
                                         alt="{{ $celebrity['name'] }}" 
                                         class="celebrity-avatar-circle"
                                         data-fallback="{{ asset('images/uploads/default-avatar.jpg') }}">
                                </div>
                            </a>
                            <div class="ceb-infor">
                                <h2><a href="{{ route('celebrities.show', $celebrity['id']) }}">{{ $celebrity['name'] }}</a></h2>
                                <span>{{ strtolower($celebrity['known_for_department']) }}</span>
                                @if(!empty($celebrity['known_for']))
                                    <div class="known-for">
                                        <small>Known for: 
                                        @foreach(array_slice($celebrity['known_for'], 0, 2) as $index => $movie)
                                            {{ $movie['title'] ?? $movie['name'] ?? '' }}@if($index < 1 && count($celebrity['known_for']) > 1), @endif
                                        @endforeach
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="no-results">
                            <div class="text-center">
                                <i class="ion-sad-outline" style="font-size: 48px; color: #ccc; margin-bottom: 20px;"></i>
                                <h3>No celebrities found</h3>
                                @if($searchQuery)
                                    <p>Try adjusting your search terms or <a href="{{ route('celebrities') }}">browse all celebrities</a></p>
                                @else
                                    <p>Unable to load celebrities at the moment. Please try again later.</p>
                                @endif
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($totalPages > 1)
                    <div class="topbar-filter">
                        <div class="pagination-container">
                            <div class="pagination">
                                @if($currentPage > 1)
                                    <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}" class="prev">
                                        <i class="ion-ios-arrow-left"></i> Previous
                                    </a>
                                @endif

                                @for($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++)
                                    <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}" 
                                       class="page {{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</a>
                                @endfor

                                @if($currentPage < $totalPages)
                                    <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}" class="next">
                                        Next <i class="ion-ios-arrow-right"></i>
                                    </a>
                                @endif
                            </div>
                            <div class="pagination-info">
                                <span>Page {{ $currentPage }} of {{ $totalPages }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-3 col-sm-12 col-xs-12">
                <div class="sidebar">
                    <div class="ads">
                        @if(isset($randomWallpaper) && !empty($randomWallpaper['backdrop_url']))
                            <div class="movie-wallpaper" style="position: relative; width: 100%; height: 250px; overflow: hidden; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.3);">
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
                            <img src="{{ asset('images/uploads/ads1.png') }}" alt="" style="width: 100%; height: 250px; object-fit: cover; border-radius: 8px;">
                        @endif
                    </div>

                    <div class="search-widget">
                        <h4 class="sb-title">Search Celebrities</h4>
                        <form action="{{ route('celebrities') }}" method="GET" class="celebrity-search-form">
                            <input type="hidden" name="sort" value="{{ $currentSort }}">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search by name..." value="{{ $searchQuery }}">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ion-ios-search"></i>
                                    </button>
                                </span>
                            </div>
                        </form>
                    </div>

                    <div class="popular-categories">
                        <h4 class="sb-title">Popular Categories</h4>
                        <ul class="category-list">
                            <li><a href="{{ route('celebrities') }}?sort=popularity">Most Popular <span class="count">{{ number_format($totalResults) }}</span></a></li>
                            <li><a href="{{ route('movies.index') }}">Browse Movies</a></li>
                            <li><a href="{{ route('community') }}">Join Community</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.celebrity-avatar-container {
    width: 150px;
    height: 150px;
    margin: 0 auto 15px;
    position: relative;
    overflow: hidden;
    border-radius: 50%;
    border: 3px solid #ddd;
    transition: all 0.3s ease;
}

.celebrity-avatar-container:hover {
    border-color: #dcf836;
    transform: scale(1.05);
}

.celebrity-avatar-circle {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.ceb-item {
    text-align: center;
    margin-bottom: 30px;
    padding: 20px;
    background: #1e1e1e;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: inline-block;
    width: calc(33.333% - 20px);
    margin-right: 30px;
    margin-bottom: 30px;
    vertical-align: top;
}

.ceb-item:nth-child(3n) {
    margin-right: 0;
}

.ceb-item:hover {
    background: #2a2a2a;
    transform: translateY(-5px);
}

.ceb-infor h2 {
    margin: 10px 0 5px;
    font-size: 18px;
}

.ceb-infor h2 a {
    color: #fff;
    text-decoration: none;
}

.ceb-infor h2 a:hover {
    color: #dcf836;
}

.ceb-infor span {
    color: #999;
    font-size: 14px;
    text-transform: capitalize;
}

.known-for {
    margin-top: 8px;
}

.known-for small {
    color: #ccc;
    font-size: 12px;
    line-height: 1.4;
}

.celebrity-items {
    display: block;
    margin-bottom: 40px;
}

.search-info {
    background: #2a2a2a;
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
    border-left: 4px solid #dcf836;
}

.search-info p {
    margin: 0;
    color: #fff;
}

.clear-search {
    color: #dcf836;
    text-decoration: none;
    margin-left: 10px;
}

.clear-search:hover {
    text-decoration: underline;
}

.topbar-filter {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 15px 0;
    border-bottom: 1px solid #333;
}

.filter-controls {
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-controls label {
    color: #fff;
    margin: 0;
}

.filter-controls select {
    background: #2a2a2a;
    color: #fff;
    border: 1px solid #444;
    padding: 8px 12px;
    border-radius: 4px;
}

.pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.pagination {
    display: flex;
    gap: 10px;
    align-items: center;
}

.pagination a {
    padding: 8px 15px;
    background: #2a2a2a;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.pagination a:hover,
.pagination a.active {
    background: #dcf836;
    color: #000;
}

.pagination-info {
    color: #999;
    font-size: 14px;
}

.no-results {
    text-align: center;
    padding: 60px 20px;
    color: #999;
}

.celebrity-search-form .input-group {
    display: flex;
}

.celebrity-search-form .form-control {
    flex: 1;
    background: #2a2a2a;
    border: 1px solid #444;
    color: #fff;
    padding: 10px 15px;
    border-radius: 4px 0 0 4px;
}

.celebrity-search-form .input-group-btn .btn {
    background: #dcf836;
    color: #000;
    border: none;
    padding: 10px 15px;
    border-radius: 0 4px 4px 0;
}

.category-list {
    list-style: none;
    padding: 0;
}

.category-list li {
    margin-bottom: 10px;
}

.category-list a {
    color: #ccc;
    text-decoration: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #333;
}

.category-list a:hover {
    color: #dcf836;
}

.category-list .count {
    background: #dcf836;
    color: #000;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 12px;
}

@media (max-width: 768px) {
    .ceb-item {
        width: calc(50% - 15px);
        margin-right: 15px;
    }
    
    .ceb-item:nth-child(3n) {
        margin-right: 15px;
    }
    
    .ceb-item:nth-child(2n) {
        margin-right: 0;
    }
    
    .topbar-filter {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
    
    .pagination-container {
        flex-direction: column;
        gap: 15px;
    }
}

@media (max-width: 480px) {
    .ceb-item {
        width: 100%;
        margin-right: 0;
    }
    
    .celebrity-avatar-container {
        width: 120px;
        height: 120px;
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
    const sortSelect = document.getElementById('sort-select');
    
    // Handle image fallbacks
    const images = document.querySelectorAll('.celebrity-avatar-circle');
    const defaultAvatar = '{{ asset('images/uploads/default-avatar.jpg') }}';
    images.forEach(function(img) {
        img.addEventListener('error', function() {
            this.src = this.dataset.fallback || defaultAvatar;
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

@endsection