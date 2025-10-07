@extends('layouts.app')

@section('title', $title)

@push('styles')
<style>
body {
    margin: 0 !important;
    padding: 0 !important;
}
.ht-header {
    margin-top: 0 !important;
    padding-top: 0 !important;
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

<div class="hero hero3">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- hero content -->
                <div class="hero-ct">
                    <h1>{{ $title }}</h1>
                    <ul class="breadcumb">
                        <li class="active"><a href="{{ route('home') }}">Home</a></li>
                        <li><span class="ion-ios-arrow-right"></span> {{ $title }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-single">
    <div class="container">
        <div class="row ipad-width2">
            <div class="col-md-8 col-sm-12 col-xs-12">
                <!-- Modern Filter Bar -->
                <div class="modern-filter-bar" style="background: #0f1419; padding: 20px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                    <div class="filter-header" style="margin-bottom: 15px; color: #abb7c4; font-size: 14px;">
                        Found <span style="color: #ec6eab; font-weight: bold;">{{ count($movies) }} movies</span> in total
                    </div>
                    
                    <form method="GET" action="{{ route('movies.index') }}" id="filter-form">
                        <div class="filter-controls" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: center; justify-content: space-between;">
                            <div class="filter-buttons" style="display: flex; flex-wrap: wrap; gap: 15px; flex: 1;">
                                <!-- Year Filter -->
                                <div class="filter-dropdown" style="position: relative;">
                                    <button type="button" class="filter-btn" data-target="year-dropdown" style="background: #020d18; color: #abb7c4; border: none; padding: 12px 20px; border-radius: 25px; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: 14px; transition: all 0.3s ease; min-width: 120px; justify-content: space-between;">
                                        <span>{{ $selectedYear ? $selectedYear : 'YEAR' }}</span>
                                        <i class="ion-ios-arrow-down" style="font-size: 12px; transition: transform 0.3s ease;"></i>
                                    </button>
                                    <div id="year-dropdown" class="dropdown-content" style="position: absolute; top: 100%; left: 0; background: #1a2332; border-radius: 10px; box-shadow: 0 8px 25px rgba(0,0,0,0.4); z-index: 1000; min-width: 200px; max-height: 250px; overflow-y: auto; opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s ease;">
                                        <a href="{{ route('movies.index', array_merge(request()->except('year'), ['page' => 1])) }}" class="dropdown-item" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease; {{ !$selectedYear ? 'background: #ec6eab; color: white;' : '' }}">All Years</a>
                                        @for($y = date('Y') + 1; $y >= 1980; $y--)
                                            <a href="{{ route('movies.index', array_merge(request()->except('page'), ['year' => $y])) }}" class="dropdown-item" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease; {{ $selectedYear == $y ? 'background: #ec6eab; color: white;' : '' }}">{{ $y }}</a>
                                        @endfor
                                    </div>
                                </div>

                                <!-- Rating Filter -->
                                <div class="filter-dropdown" style="position: relative;">
                                    <button type="button" class="filter-btn" data-target="rating-dropdown" style="background: #020d18; color: #abb7c4; border: none; padding: 12px 20px; border-radius: 25px; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: 14px; transition: all 0.3s ease; min-width: 120px; justify-content: space-between;">
                                        <span>{{ $selectedRating ? $selectedRating.'+' : 'RATING' }}</span>
                                        <i class="ion-ios-arrow-down" style="font-size: 12px; transition: transform 0.3s ease;"></i>
                                    </button>
                                    <div id="rating-dropdown" class="dropdown-content" style="position: absolute; top: 100%; left: 0; background: #1a2332; border-radius: 10px; box-shadow: 0 8px 25px rgba(0,0,0,0.4); z-index: 1000; min-width: 200px; opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s ease;">
                                        <a href="{{ route('movies.index', array_merge(request()->except('rating'), ['page' => 1])) }}" class="dropdown-item" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease; {{ !$selectedRating ? 'background: #ec6eab; color: white;' : '' }}">All Ratings</a>
                                        <a href="{{ route('movies.index', array_merge(request()->except('page'), ['rating' => 9])) }}" class="dropdown-item" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease; {{ $selectedRating == 9 ? 'background: #ec6eab; color: white;' : '' }}">9+ Outstanding</a>
                                        <a href="{{ route('movies.index', array_merge(request()->except('page'), ['rating' => 8])) }}" class="dropdown-item" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease; {{ $selectedRating == 8 ? 'background: #ec6eab; color: white;' : '' }}">8+ Excellent</a>
                                        <a href="{{ route('movies.index', array_merge(request()->except('page'), ['rating' => 7])) }}" class="dropdown-item" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease; {{ $selectedRating == 7 ? 'background: #ec6eab; color: white;' : '' }}">7+ Great</a>
                                        <a href="{{ route('movies.index', array_merge(request()->except('page'), ['rating' => 6])) }}" class="dropdown-item" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease; {{ $selectedRating == 6 ? 'background: #ec6eab; color: white;' : '' }}">6+ Good</a>
                                        <a href="{{ route('movies.index', array_merge(request()->except('page'), ['rating' => 5])) }}" class="dropdown-item" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease; {{ $selectedRating == 5 ? 'background: #ec6eab; color: white;' : '' }}">5+ Average</a>
                                    </div>
                                </div>

                                <!-- Popular Filter -->
                                <div class="filter-dropdown" style="position: relative;">
                                    <button type="button" class="filter-btn" data-target="popular-dropdown" style="background: #020d18; color: #abb7c4; border: none; padding: 12px 20px; border-radius: 25px; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: 14px; transition: all 0.3s ease; min-width: 120px; justify-content: space-between;">
                                        <span>{{ ucfirst(str_replace('-', ' ', $category)) ?: 'POPULAR' }}</span>
                                        <i class="ion-ios-arrow-down" style="font-size: 12px; transition: transform 0.3s ease;"></i>
                                    </button>
                                    <div id="popular-dropdown" class="dropdown-content" style="position: absolute; top: 100%; left: 0; background: #1a2332; border-radius: 10px; box-shadow: 0 8px 25px rgba(0,0,0,0.4); z-index: 1000; min-width: 200px; opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s ease;">
                                        <a href="{{ route('movies.index', array_merge(request()->except(['category', 'page']))) }}" class="dropdown-item" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease; {{ !$category ? 'background: #ec6eab; color: white;' : '' }}">All Movies</a>
                                        <a href="{{ route('movies.index', array_merge(request()->except('page'), ['category' => 'popular'])) }}" class="dropdown-item" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease; {{ $category == 'popular' ? 'background: #ec6eab; color: white;' : '' }}">Popular</a>
                                        <a href="{{ route('movies.index', array_merge(request()->except('page'), ['category' => 'top-rated'])) }}" class="dropdown-item" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease; {{ $category == 'top-rated' ? 'background: #ec6eab; color: white;' : '' }}">Top Rated</a>
                                        <a href="{{ route('movies.index', array_merge(request()->except('page'), ['category' => 'trending'])) }}" class="dropdown-item" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease; {{ $category == 'trending' ? 'background: #ec6eab; color: white;' : '' }}">Trending</a>
                                        <a href="{{ route('movies.index', array_merge(request()->except('page'), ['category' => 'upcoming'])) }}" class="dropdown-item" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease; {{ $category == 'upcoming' ? 'background: #ec6eab; color: white;' : '' }}">Upcoming</a>
                                    </div>
                                </div>

                                <!-- Genre Filter -->
                                <div class="filter-dropdown" style="position: relative;">
                                    <button type="button" class="filter-btn" data-target="genre-dropdown" style="background: #020d18; color: #abb7c4; border: none; padding: 12px 20px; border-radius: 25px; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: 14px; transition: all 0.3s ease; min-width: 120px; justify-content: space-between;">
                                        <span>{{ $selectedGenre ? collect($genres)->firstWhere('id', $selectedGenre)['name'] ?? 'GENRE' : 'GENRE' }}</span>
                                        <i class="ion-ios-arrow-down" style="font-size: 12px; transition: transform 0.3s ease;"></i>
                                    </button>
                                    <div id="genre-dropdown" class="dropdown-content" style="position: absolute; top: 100%; left: 0; background: #1a2332; border-radius: 10px; box-shadow: 0 8px 25px rgba(0,0,0,0.4); z-index: 1000; min-width: 200px; max-height: 250px; overflow-y: auto; opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s ease;">
                                        <a href="{{ route('movies.index', array_merge(request()->except('genre'), ['page' => 1])) }}" class="dropdown-item" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease; {{ !$selectedGenre ? 'background: #ec6eab; color: white;' : '' }}">All Genres</a>
                                        @foreach($genres as $genre)
                                            <a href="{{ route('movies.index', array_merge(request()->except('page'), ['genre' => $genre['id']])) }}" class="dropdown-item" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease; {{ $selectedGenre == $genre['id'] ? 'background: #ec6eab; color: white;' : '' }}">{{ $genre['name'] }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Clear All Button -->
                            @if(request()->hasAny(['genre', 'year', 'rating', 'category']))
                                <div class="filter-clear" style="margin-left: auto;">
                                    <a href="{{ route('movies.index') }}" style="background: #ec6eab; color: white; padding: 12px 20px; border-radius: 25px; text-decoration: none; font-size: 14px; transition: all 0.3s ease;">Clear All</a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>

                @if($error)
                    <div class="alert alert-danger">
                        <i class="ion-alert-circled"></i> {{ $error }}
                    </div>
                @endif

                @if(!empty($movies))
                    <div class="flex-wrap-movielist">
                        @foreach($movies as $movie)
                            <div class="movie-item-style-2 movie-item-style-1">
                                <a href="{{ route('movies.show', $movie['id']) }}">
                                    <img src="{{ app('App\Services\MovieService')->getImageUrl($movie['poster_path'] ?? null) }}" 
                                         alt="{{ $movie['title'] ?? 'Movie Poster' }}">
                                </a>
                                <div class="mv-item-infor">
                                    <h6><a href="{{ route('movies.show', $movie['id']) }}">{{ $movie['title'] ?? 'Untitled' }}</a></h6>
                                    <p class="rate">
                                        @php
                                            $rating = app('App\Services\MovieService')->getRatingStars($movie['vote_average'] ?? 0);
                                        @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $rating)
                                                <i class="ion-android-star"></i>
                                            @else
                                                <i class="ion-android-star-outline"></i>
                                            @endif
                                        @endfor
                                        <span class="fr">{{ number_format($movie['vote_average'] ?? 0, 1) }}/10</span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($total_pages > 1)
                        <div class="topbar-filter">
                            <div class="pagination2">
                                @php
                                    $paginationParams = array_filter([
                                        'category' => $category,
                                        'genre' => $selectedGenre,
                                        'year' => $selectedYear,
                                        'rating' => $selectedRating,
                                    ]);
                                @endphp
                                
                                @if($current_page > 1)
                                    <span><a href="{{ route('movies.index', array_merge($paginationParams, ['page' => $current_page - 1])) }}">
                                        <i class="ion-arrow-left-b"></i></a></span>
                                @endif

                                @php
                                    $start = max(1, $current_page - 2);
                                    $end = min($total_pages, $current_page + 2);
                                @endphp

                                @for($i = $start; $i <= $end; $i++)
                                    <span><a href="{{ route('movies.index', array_merge($paginationParams, ['page' => $i])) }}" 
                                        class="{{ $i == $current_page ? 'active' : '' }}">{{ $i }}</a></span>
                                @endfor

                                @if($current_page < $total_pages)
                                    <span><a href="{{ route('movies.index', array_merge($paginationParams, ['page' => $current_page + 1])) }}">
                                        <i class="ion-arrow-right-b"></i></a></span>
                                @endif
                            </div>
                        </div>
                    @endif
                @else
                    <div class="no-results">
                        <h3>No movies found</h3>
                        <p>Try adjusting your filters or check back later for new content.</p>
                    </div>
                @endif
            </div>

            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="sidebar">
                    <div class="searh-form">
                        <h4 class="sb-title">Search for Movies</h4>
                        <form class="form-style-1" method="GET" action="{{ route('movies.search') }}">
                            <div class="row">
                                <div class="col-md-12 form-it">
                                    <label>Movie name</label>
                                    <input type="text" name="q" placeholder="Enter keywords here" value="{{ request('q') }}">
                                </div>
                                <div class="col-md-12 form-it">
                                    <input class="submit" type="submit" value="Search">
                                </div>
                            </div>
                        </form>
                    </div>

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
                            <img src="{{ asset('images/uploads/ads1.png') }}" alt="">
                        @endif
                    </div>

                    <div class="celebrities">
                        <h4 class="sb-title">Celebrities</h4>
                        <div class="celeb-item">
                            <a href="{{ route('celebritysingle') }}"><img src="{{ asset('images/uploads/ava1.jpg') }}" alt="" width="70" height="70"></a>
                            <div class="celeb-author">
                                <h6><a href="{{ route('celebritysingle') }}">Samuel N. Jack</a></h6>
                                <span>Actor</span>
                            </div>
                        </div>
                        <div class="celeb-item">
                            <a href="{{ route('celebritysingle') }}"><img src="{{ asset('images/uploads/ava2.jpg') }}" alt="" width="70" height="70"></a>
                            <div class="celeb-author">
                                <h6><a href="{{ route('celebritysingle') }}">Benjamin Carroll</a></h6>
                                <span>Actor</span>
                            </div>
                        </div>
                        <div class="celeb-item">
                            <a href="{{ route('celebritysingle') }}"><img src="{{ asset('images/uploads/ava3.jpg') }}" alt="" width="70" height="70"></a>
                            <div class="celeb-author">
                                <h6><a href="{{ route('celebritysingle') }}">Beverly Griffin</a></h6>
                                <span>Actor</span>
                            </div>
                        </div>
                        <div class="celeb-item">
                            <a href="{{ route('celebritysingle') }}"><img src="{{ asset('images/uploads/ava4.jpg') }}" alt="" width="70" height="70"></a>
                            <div class="celeb-author">
                                <h6><a href="{{ route('celebritysingle') }}">Justin Weaver</a></h6>
                                <span>Actor</span>
                            </div>
                        </div>
                        <a href="{{ route('celebrities') }}" class="btn">See all celebrities<i class="ion-ios-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
/* Filter dropdown responsive styles */
@media (max-width: 768px) {
    .filter-controls {
        flex-direction: column !important;
        align-items: stretch !important;
    }
    .filter-buttons {
        justify-content: center !important;
        margin-bottom: 15px;
    }
    .filter-search {
        justify-content: center !important;
    }
    .search-input-wrapper input {
        width: 200px !important;
    }
}

@media (max-width: 480px) {
    .filter-buttons {
        flex-direction: column !important;
        align-items: center !important;
    }
    .filter-dropdown {
        width: 100% !important;
        max-width: 250px;
    }
    .filter-btn {
        width: 100% !important;
        justify-content: space-between !important;
    }
}

/* Hover effects */
.filter-btn:hover {
    background: #020d18 !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.dropdown-item:hover {
    background: #ec6eab !important;
    color: white !important;
}

.search-input-wrapper input:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(221, 0, 63, 0.3);
}

/* Dropdown animations */
.dropdown-content.show {
    opacity: 1 !important;
    visibility: visible !important;
    transform: translateY(0) !important;
}

.filter-btn.active .ion-ios-arrow-down {
    transform: rotate(180deg);
}
</style>

<script>
// Filter dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    // Handle dropdown toggles
    const filterButtons = document.querySelectorAll('.filter-btn');
    const dropdownContents = document.querySelectorAll('.dropdown-content');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const targetId = this.getAttribute('data-target');
            const targetDropdown = document.getElementById(targetId);
            const isActive = this.classList.contains('active');
            
            // Close all dropdowns
            filterButtons.forEach(btn => btn.classList.remove('active'));
            dropdownContents.forEach(dropdown => dropdown.classList.remove('show'));
            
            // Toggle current dropdown
            if (!isActive && targetDropdown) {
                this.classList.add('active');
                targetDropdown.classList.add('show');
            }
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.filter-dropdown')) {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            dropdownContents.forEach(dropdown => dropdown.classList.remove('show'));
        }
    });
    
    // Handle search input focus effects
    const searchInput = document.querySelector('.search-input-wrapper input');
    if (searchInput) {
        searchInput.addEventListener('focus', function() {
            this.style.transform = 'scale(1.02)';
        });
        
        searchInput.addEventListener('blur', function() {
            this.style.transform = 'scale(1)';
        });
        
        // Handle Enter key for search
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.closest('form').submit();
            }
        });
    }
    
    // Legacy search functionality for header search
    const searchIcon = document.getElementById('search-icon');
    const headerSearchInput = document.getElementById('search-query');
    const searchType = document.getElementById('search-type');
    
    function performHeaderSearch() {
        if (!headerSearchInput || !searchType) return;
        
        const query = headerSearchInput.value.trim();
        const type = searchType.value;
        
        if (query) {
            if (type === 'movies') {
                const searchUrl = `{{ route('movies.search') }}?q=${encodeURIComponent(query)}`;
                window.location.href = searchUrl;
            } else {
                // For TV shows, redirect to home search
                const searchUrl = `{{ route('home.search') }}?q=${encodeURIComponent(query)}&type=${type}`;
                window.location.href = searchUrl;
            }
        }
    }
    
    // Search on icon click
    if (searchIcon) {
        searchIcon.addEventListener('click', performHeaderSearch);
    }
    
    // Search on Enter key press for header search
    if (headerSearchInput) {
        headerSearchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performHeaderSearch();
            }
        });
    }
    
    // Smooth animations for filter buttons
    filterButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        button.addEventListener('mouseleave', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateY(0)';
            }
        });
    });
});

// Initialize movie grid functionality
$(document).ready(function() {
    // Add any additional movie-specific functionality here
});
</script>
@endsection
