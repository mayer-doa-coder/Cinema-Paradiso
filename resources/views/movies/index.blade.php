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
                <!-- Movie Categories Navigation -->
                <div class="topbar-filter">
                    <p>Found <span>{{ count($movies) }} movies</span> in total</p>
                    <label>Category:</label>
                    <div class="pagination2">
                        <span><a href="{{ route('movies.index', ['category' => 'popular']) }}" 
                            class="{{ $category == 'popular' ? 'active' : '' }}">Popular</a></span>
                        <span><a href="{{ route('movies.index', ['category' => 'top-rated']) }}" 
                            class="{{ $category == 'top-rated' ? 'active' : '' }}">Top Rated</a></span>
                        <span><a href="{{ route('movies.index', ['category' => 'trending']) }}" 
                            class="{{ $category == 'trending' ? 'active' : '' }}">Trending</a></span>
                        <span><a href="{{ route('movies.index', ['category' => 'upcoming']) }}" 
                            class="{{ $category == 'upcoming' ? 'active' : '' }}">Upcoming</a></span>
                    </div>
                </div>

                <!-- Advanced Filters Section -->
                <div class="topbar-filter" style="margin-top: 20px; border-top: 1px solid #405266; padding-top: 20px;">
                    <form method="GET" action="{{ route('movies.index') }}" id="filter-form">
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <label>Browse by Genre:</label>
                                <select name="genre" class="form-control" style="background: #020d18; color: #abb7c4; border: 1px solid #405266; padding: 8px;">
                                    <option value="">All Genres</option>
                                    @foreach($genres as $genre)
                                        <option value="{{ $genre['id'] }}" {{ $selectedGenre == $genre['id'] ? 'selected' : '' }}>
                                            {{ $genre['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <label>Browse by Year:</label>
                                <select name="year" class="form-control" style="background: #020d18; color: #abb7c4; border: 1px solid #405266; padding: 8px;">
                                    <option value="">All Years</option>
                                    @for($y = date('Y') + 1; $y >= 1980; $y--)
                                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <label>Minimum Rating:</label>
                                <select name="rating" class="form-control" style="background: #020d18; color: #abb7c4; border: 1px solid #405266; padding: 8px;">
                                    <option value="">All Ratings</option>
                                    <option value="9" {{ $selectedRating == 9 ? 'selected' : '' }}>9+ Outstanding</option>
                                    <option value="8" {{ $selectedRating == 8 ? 'selected' : '' }}>8+ Excellent</option>
                                    <option value="7" {{ $selectedRating == 7 ? 'selected' : '' }}>7+ Great</option>
                                    <option value="6" {{ $selectedRating == 6 ? 'selected' : '' }}>6+ Good</option>
                                    <option value="5" {{ $selectedRating == 5 ? 'selected' : '' }}>5+ Average</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6" style="padding-top: 22px;">
                                <button type="submit" class="btn btn-default" style="background: #dd003f; color: white; padding: 10px 20px; border: none; border-radius: 3px;">Apply Filters</button>
                                <a href="{{ route('movies.index') }}" class="btn btn-default" style="background: #405266; color: white; padding: 10px 20px; margin-left: 5px; display: inline-block; text-decoration: none; border-radius: 3px;">Clear</a>
                            </div>
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
                                <img src="{{ app('App\Services\MovieService')->getImageUrl($movie['poster_path'] ?? null) }}" 
                                     alt="{{ $movie['title'] ?? 'Movie Poster' }}">
                                <div class="hvr-inner">
                                    <a href="{{ route('movies.show', $movie['id']) }}">Read more <i class="ion-android-arrow-dropright"></i></a>
                                </div>
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

// Add any movie-specific JavaScript here
$(document).ready(function() {
    // Initialize any movie grid functionality
});
</script>
@endsection