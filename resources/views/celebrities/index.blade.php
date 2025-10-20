@extends('layouts.app')

@section('title', 'Celebrities - Cinema Paradiso')

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

/* Fix active celebrities link to maintain normal color */
header .navbar-default .navbar-nav li.active a,
header .navbar-default .navbar-nav li.active a:focus,
header .navbar-default .navbar-nav li.active a:active {
    color: #abb7c4 !important;
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

/* Override global first-child margin */
h1:first-child, h2:first-child, h3:first-child, h4:first-child, h5:first-child, p:first-child {
    margin-top: 20px !important;
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
                    <p>Found <span>{{ number_format($totalResults) }} celebrities</span> 
                    @if($isAlphabetical)
                        starting with "{{ $currentLetter }}"
                    @else
                        in total
                    @endif
                    </p>
                    <div class="filter-controls">
                        <label>Sort by:</label>
                        <select id="sort-select">
                            <option value="popularity" {{ $currentSort == 'popularity' ? 'selected' : '' }}>Popularity Descending</option>
                            <option value="name" {{ $currentSort == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                        </select>
                    </div>
                </div>

                @if($isAlphabetical)
                    <!-- Alphabetical Navigation -->
                    <div class="alphabet-navigation" style="background: #1e1e1e; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                        <h4 style="color: #fff; margin-bottom: 15px; font-size: 16px;">Browse by Letter:</h4>
                        <div class="alphabet-letters" style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @foreach($availableLetters as $letter)
                                <a href="{{ route('celebrities') }}?sort=name&letter={{ $letter }}&subpage=1" 
                                   class="letter-btn {{ $currentLetter == $letter ? 'active' : '' }}">
                                    {{ $letter }}
                                </a>
                            @endforeach
                        </div>
                        @if($totalSubPages > 1)
                            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #333;">
                                <span style="color: #ccc; font-size: 14px;">{{ $currentLetter }} - Page {{ $currentSubPage }} of {{ $totalSubPages }}</span>
                            </div>
                        @endif
                    </div>
                @endif

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
                        <div class="pagination2">
                            @if($isAlphabetical)
                                <!-- Alphabetical Sub-Page Navigation -->
                                @if($currentSubPage > 1)
                                    <span><a href="{{ route('celebrities') }}?sort=name&letter={{ $currentLetter }}&subpage={{ $currentSubPage - 1 }}">
                                        <i class="ion-arrow-left-b"></i></a></span>
                                @endif

                                @php
                                    $start = max(1, $currentSubPage - 2);
                                    $end = min($totalSubPages, $currentSubPage + 2);
                                @endphp

                                @for($i = $start; $i <= $end; $i++)
                                    <span><a href="{{ route('celebrities') }}?sort=name&letter={{ $currentLetter }}&subpage={{ $i }}" 
                                        class="{{ $i == $currentSubPage ? 'active' : '' }}">{{ $currentLetter }}{{ $i }}</a></span>
                                @endfor

                                @if($currentSubPage < $totalSubPages)
                                    <span><a href="{{ route('celebrities') }}?sort=name&letter={{ $currentLetter }}&subpage={{ $currentSubPage + 1 }}">
                                        <i class="ion-arrow-right-b"></i></a></span>
                                @endif
                            @else
                                <!-- Regular Numeric Pagination -->
                                @if($currentPage > 1)
                                    <span><a href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1, 'sort' => $currentSort]) }}">
                                        <i class="ion-arrow-left-b"></i></a></span>
                                @endif

                                @php
                                    $start = max(1, $currentPage - 2);
                                    $end = min($totalPages, $currentPage + 2);
                                @endphp

                                @for($i = $start; $i <= $end; $i++)
                                    <span><a href="{{ request()->fullUrlWithQuery(['page' => $i, 'sort' => $currentSort]) }}" 
                                        class="{{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</a></span>
                                @endfor

                                @if($currentPage < $totalPages)
                                    <span><a href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1, 'sort' => $currentSort]) }}">
                                        <i class="ion-arrow-right-b"></i></a></span>
                                @endif
                            @endif
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
                            <li><a href="{{ route('celebrities') }}?sort=popularity">Most Popular</a></li>
                            <li><a href="{{ route('celebrities') }}?sort=name">Alphabetical (A-Z)</a></li>
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
    width: 120px;
    background-color: #020d18;
    height: 120px;
    margin: 0 auto 20px;
    position: relative;
    overflow: hidden;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.celebrity-avatar-container:hover {
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
    transition: all 0.3s ease;
    display: inline-block;
    width: 19%;
    vertical-align: top;
    box-sizing: border-box;
}

.ceb-item:nth-child(4n) {
    margin-right: 0;
}

.ceb-item:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

.ceb-infor h2 {
    margin: 15px 0 8px;
    font-size: 18px;
}

.ceb-infor h2 a {
    color: #fff;
    text-decoration: none;
}

.ceb-infor h2 a:hover {
    color: #e9d736;
}

.ceb-infor span {
    color: #999;
    font-size: 14px;
    text-transform: capitalize;
    display: block;
    margin-bottom: 10px;
}

.celebrity-items {
    display: block;
    margin-bottom: 50px;
    padding: 0;
}

.search-info {
    background: #2a2a2a;
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
    border-left: 4px solid #e9d736;
}

.search-info p {
    margin: 0;
    color: #fff;
}

.clear-search {
    color: #e9d736;
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
    color: #000;
    padding: 10px 15px;
}

.celebrity-search-form .input-group-btn .btn {
    background: #ec6eab;
    color: #000;
    padding: 5px 15px;
    margin-bottom: 30px;
}

.category-list li {
    margin-bottom: 10px;
}

.category-list a:hover {
    font-weight: bold;
    color: #e9d736;
}

/* Alphabet Navigation Styles */
.alphabet-navigation {
    background: #1e1e1e !important;
    padding: 20px !important;
    border-radius: 8px !important;
    margin-bottom: 30px !important;
}

.alphabet-letters {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 8px !important;
}

.letter-btn {
    display: inline-block !important;
    padding: 8px 12px !important;
    text-decoration: none !important;
    border-radius: 4px !important;
    font-weight: bold !important;
    transition: all 0.3s ease !important;
    font-size: 14px !important;
}

.letter-btn:hover {
    background: #e9d736 !important;
    color: #000 !important;
    transform: translateY(-1px);
}

.letter-btn.active {
    background: #e9d736 !important;
    color: #000 !important;
}

@media (max-width: 1200px) {
    .ceb-item {
        width: calc(33.333% - 27px);
        margin-right: 40px;
    }
    
    .ceb-item:nth-child(4n) {
        margin-right: 40px;
    }
    
    .ceb-item:nth-child(3n) {
        margin-right: 0;
    }
}

@media (max-width: 992px) {
    .ceb-item {
        width: calc(50% - 20px);
        margin-right: 40px;
        margin-bottom: 35px;
    }
    
    .ceb-item:nth-child(4n) {
        margin-right: 40px;
    }
    
    .ceb-item:nth-child(3n) {
        margin-right: 40px;
    }
    
    .ceb-item:nth-child(2n) {
        margin-right: 0;
    }
}

@media (max-width: 768px) {
    .ceb-item {
        width: calc(50% - 15px);
        margin-right: 30px;
        margin-bottom: 30px;
        padding: 20px 10px;
    }
    
    .ceb-item:nth-child(4n) {
        margin-right: 30px;
    }
    
    .ceb-item:nth-child(3n) {
        margin-right: 30px;
    }
    
    .ceb-item:nth-child(2n) {
        margin-right: 0;
    }
    
    .celebrity-avatar-container {
        width: 100px;
        height: 100px;
        margin-bottom: 15px;
    }
    
    .topbar-filter {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
}

@media (max-width: 480px) {
    .ceb-item {
        width: 100%;
        margin-right: 0;
    }
    
    .celebrity-avatar-container {
        width: 100px;
        height: 100px;
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
    const imagesList = document.querySelectorAll('.celebrity-avatar-circle');
    const defaultAvatar = "{{ asset('images/uploads/default-avatar.jpg') }}";
    imagesList.forEach(function(img) {
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
            
            if (this.value === 'name') {
                // For alphabetical sorting, redirect to letter A, page 1
                url.searchParams.set('sort', 'name');
                url.searchParams.set('letter', 'A');
                url.searchParams.set('subpage', '1');
                url.searchParams.delete('page'); // Remove regular page parameter
            } else {
                // For popularity sorting, use regular pagination
                url.searchParams.set('sort', this.value);
                url.searchParams.set('page', '1');
                url.searchParams.delete('letter'); // Remove alphabetical parameters
                url.searchParams.delete('subpage');
            }
            
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