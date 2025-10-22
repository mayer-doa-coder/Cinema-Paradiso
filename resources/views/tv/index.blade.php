@extends('layouts.app')

@section('name', $title)

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
							<a class="btn btn-default lv1" href="{{ route('tv.index') }}">
							TV Shows
							<!-- <ul class="sub-menu">
								<li><a href="{{ route('tv.index', ['category' => 'popular']) }}">Popular</a></li>
								<li><a href="{{ route('tv.index', ['category' => 'top-rated']) }}">Top Rated</a></li>
								<li><a href="{{ route('tv.index', ['category' => 'trending']) }}">Trending</a></li>
								<li><a href="{{ route('tv.index', ['category' => 'on-air']) }}">On Air</a></li>
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
        <div class="row ipad-width2" id="main-row">
            <div class="col-md-8 col-sm-12 col-xs-12" id="main-content">
                <!-- Modern Filter Bar -->
                <div class="modern-filter-bar" style="padding: 20px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                    <div class="filter-header" style="margin-bottom: 15px; color: #abb7c4; font-size: 14px;">
                        Found <span style="color: #ec6eab; font-weight: bold;">{{ isset($pagination['total_results']) ? $pagination['total_results'] : count($tvShows) }} TV shows</span> in total
                    </div>
                    
                    <form method="GET" action="{{ route('tv.index') }}" id="filter-form">
                        <div class="filter-controls" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: center; justify-content: space-between;">
                            <div class="filter-buttons" style="display: flex; flex-wrap: wrap; gap: 15px; flex: 1;">
                                <!-- Year Filter -->
                                <div class="filter-dropdown" style="position: relative;">
                                    <button type="button" class="filter-btn" data-target="year-dropdown" style="background: #020d18; color: #abb7c4; border: none; padding: 12px 20px; border-radius: 25px; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: 14px; transition: all 0.3s ease; min-width: 120px; justify-content: space-between;">
                                        <span>{{ request('year') ? request('year') : 'YEAR' }}</span>
                                        <i class="ion-ios-arrow-down" style="font-size: 12px; transition: transform 0.3s ease;"></i>
                                    </button>
                                    <div id="year-dropdown" class="dropdown-content" style="position: absolute; top: 100%; left: 0; background: #1a2332; border-radius: 10px; box-shadow: 0 8px 25px rgba(0,0,0,0.4); z-index: 1000; min-width: 200px; max-height: 250px; overflow-y: auto; opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s ease;">
                                        <a href="{{ route('tv.index', array_merge(request()->except('year'), ['page' => 1])) }}" class="dropdown-item {{ !request('year') ? 'active' : '' }}" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease;">All Years</a>
                                        @for($y = date('Y') + 1; $y >= 1980; $y--)
                                            <a href="{{ route('tv.index', array_merge(request()->except('page'), ['year' => $y])) }}" class="dropdown-item {{ request('year') == $y ? 'active' : '' }}" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease;">{{ $y }}</a>
                                        @endfor
                                    </div>
                                </div>

                                <!-- Rating Filter -->
                                <div class="filter-dropdown" style="position: relative;">
                                    <button type="button" class="filter-btn" data-target="rating-dropdown" style="background: #020d18; color: #abb7c4; border: none; padding: 12px 20px; border-radius: 25px; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: 14px; transition: all 0.3s ease; min-width: 120px; justify-content: space-between;">
                                        <span>{{ request('rating') ? request('rating').'+' : 'RATING' }}</span>
                                        <i class="ion-ios-arrow-down" style="font-size: 12px; transition: transform 0.3s ease;"></i>
                                    </button>
                                    <div id="rating-dropdown" class="dropdown-content" style="position: absolute; top: 100%; left: 0; background: #1a2332; border-radius: 10px; box-shadow: 0 8px 25px rgba(0,0,0,0.4); z-index: 1000; min-width: 200px; opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s ease;">
                                        <a href="{{ route('tv.index', array_merge(request()->except('rating'), ['page' => 1])) }}" class="dropdown-item {{ !request('rating') ? 'active' : '' }}" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease;">All Ratings</a>
                                        <a href="{{ route('tv.index', array_merge(request()->except('page'), ['rating' => 9])) }}" class="dropdown-item {{ request('rating') == 9 ? 'active' : '' }}" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease;">9+ Outstanding</a>
                                        <a href="{{ route('tv.index', array_merge(request()->except('page'), ['rating' => 8])) }}" class="dropdown-item {{ request('rating') == 8 ? 'active' : '' }}" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease;">8+ Excellent</a>
                                        <a href="{{ route('tv.index', array_merge(request()->except('page'), ['rating' => 7])) }}" class="dropdown-item {{ request('rating') == 7 ? 'active' : '' }}" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease;">7+ Great</a>
                                        <a href="{{ route('tv.index', array_merge(request()->except('page'), ['rating' => 6])) }}" class="dropdown-item {{ request('rating') == 6 ? 'active' : '' }}" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease;">6+ Good</a>
                                        <a href="{{ route('tv.index', array_merge(request()->except('page'), ['rating' => 5])) }}" class="dropdown-item {{ request('rating') == 5 ? 'active' : '' }}" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease;">5+ Average</a>
                                    </div>
                                </div>

                                <!-- Category Filter -->
                                <div class="filter-dropdown" style="position: relative;">
                                    <button type="button" class="filter-btn" data-target="category-dropdown" style="background: #020d18; color: #abb7c4; border: none; padding: 12px 20px; border-radius: 25px; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: 14px; transition: all 0.3s ease; min-width: 120px; justify-content: space-between;">
                                        <span>{{ ucfirst(str_replace('-', ' ', $currentCategory)) }}</span>
                                        <i class="ion-ios-arrow-down" style="font-size: 12px; transition: transform 0.3s ease;"></i>
                                    </button>
                                    <div id="category-dropdown" class="dropdown-content" style="position: absolute; top: 100%; left: 0; background: #1a2332; border-radius: 10px; box-shadow: 0 8px 25px rgba(0,0,0,0.4); z-index: 1000; min-width: 200px; opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s ease;">
                                        <a href="{{ route('tv.index', array_merge(request()->except(['category', 'page']))) }}" class="dropdown-item {{ !$currentCategory || $currentCategory == 'popular' ? 'active' : '' }}" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease;">Popular</a>
                                        <a href="{{ route('tv.index', array_merge(request()->except('page'), ['category' => 'top-rated'])) }}" class="dropdown-item {{ $currentCategory == 'top-rated' ? 'active' : '' }}" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease;">Top Rated</a>
                                        <a href="{{ route('tv.index', array_merge(request()->except('page'), ['category' => 'trending'])) }}" class="dropdown-item {{ $currentCategory == 'trending' ? 'active' : '' }}" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease;">Trending</a>
                                        <a href="{{ route('tv.index', array_merge(request()->except('page'), ['category' => 'airing-today'])) }}" class="dropdown-item {{ $currentCategory == 'airing-today' ? 'active' : '' }}" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease;">Airing Today</a>
                                        <a href="{{ route('tv.index', array_merge(request()->except('page'), ['category' => 'on-air'])) }}" class="dropdown-item {{ $currentCategory == 'on-air' ? 'active' : '' }}" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease;">On Air</a>
                                    </div>
                                </div>

                                <!-- Genre Filter -->
                                <div class="filter-dropdown" style="position: relative;">
                                    <button type="button" class="filter-btn" data-target="genre-dropdown" style="background: #020d18; color: #abb7c4; border: none; padding: 12px 20px; border-radius: 25px; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: 14px; transition: all 0.3s ease; min-width: 120px; justify-content: space-between;">
                                        <span>{{ request('genre') ? collect($genres)->firstWhere('id', request('genre'))['name'] ?? 'GENRE' : 'GENRE' }}</span>
                                        <i class="ion-ios-arrow-down" style="font-size: 12px; transition: transform 0.3s ease;"></i>
                                    </button>
                                    <div id="genre-dropdown" class="dropdown-content" style="position: absolute; top: 100%; left: 0; background: #1a2332; border-radius: 10px; box-shadow: 0 8px 25px rgba(0,0,0,0.4); z-index: 1000; min-width: 200px; max-height: 250px; overflow-y: auto; opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s ease;">
                                        <a href="{{ route('tv.index', array_merge(request()->except('genre'), ['page' => 1])) }}" class="dropdown-item {{ !request('genre') ? 'active' : '' }}" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease;">All Genres</a>
                                        @foreach($genres as $genre)
                                            <a href="{{ route('tv.index', array_merge(request()->except('page'), ['genre' => $genre['id']])) }}" class="dropdown-item {{ request('genre') == $genre['id'] ? 'active' : '' }}" style="display: block; padding: 12px 20px; color: #abb7c4; text-decoration: none; transition: background 0.2s ease;">{{ $genre['name'] }}</a>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Grid Toggle -->
                                <div class="grid-toggle" style="margin-left: 15px;">
                                    <button type="button" id="grid-toggle-btn" style="background: #020d18; color: #abb7c4; border: none; padding: 12px 15px; border-radius: 25px; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: 14px; transition: all 0.3s ease;">
                                        <i class="ion-grid" style="font-size: 16px;"></i>
                                        <span>Full Grid</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Clear All Button -->
                            @if(request()->hasAny(['genre', 'year', 'rating', 'category']))
                                <div class="filter-clear" style="margin-left: auto;">
                                    <a href="{{ route('tv.index') }}" style="background: #ec6eab; color: white; padding: 12px 20px; border-radius: 25px; text-decoration: none; font-size: 14px; transition: all 0.3s ease;">Clear All</a>
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

                @if(!empty($tvShows))
                    <div class="flex-wrap-movielist">
                        @foreach($tvShows as $tvShow)
                            <div class="movie-item-style-2 movie-item-style-1">
                                <a href="{{ route('tv.show', $tvShow['id']) }}">
                                    <img src="{{ $tvShow['poster_url'] ?? asset('images/uploads/movie-placeholder.jpg') }}" 
                                         alt="{{ $tvShow['name'] ?? 'TV Show Poster' }}">
                                </a>
                                <div class="mv-item-infor">
                                    <h6><a href="{{ route('tv.show', $tvShow['id']) }}">{{ $tvShow['name'] ?? 'Untitled' }}</a></h6>
                                    <p class="rate">
                                        @php
                                            $rating = round(($tvShow['vote_average'] ?? 0) / 2);
                                        @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $rating)
                                                <i class="ion-android-star"></i>
                                            @else
                                                <i class="ion-android-star-outline"></i>
                                            @endif
                                        @endfor
                                        <span class="fr">{{ number_format($tvShow['vote_average'] ?? 0, 1) }}/10</span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if(isset($pagination) && ($pagination['total_pages'] ?? 1) > 1)
                        <div class="topbar-filter">
                            <div class="pagination2">
                                @php
                                    $paginationParams = array_filter([
                                        'category' => $currentCategory,
                                        'genre' => request('genre'),
                                        'year' => request('year'),
                                        'rating' => request('rating'),
                                    ]);
                                    $current_page = $pagination['page'] ?? 1;
                                    $total_pages = $pagination['total_pages'] ?? 1;
                                @endphp
                                
                                @if($current_page > 1)
                                    <span><a href="{{ route('tv.index', array_merge($paginationParams, ['page' => $current_page - 1])) }}">
                                        <i class="ion-arrow-left-b"></i></a></span>
                                @endif

                                @php
                                    $start = max(1, $current_page - 2);
                                    $end = min($total_pages, $current_page + 2);
                                @endphp

                                @for($i = $start; $i <= $end; $i++)
                                    <span><a href="{{ route('tv.index', array_merge($paginationParams, ['page' => $i])) }}" 
                                        class="{{ $i == $current_page ? 'active' : '' }}">{{ $i }}</a></span>
                                @endfor

                                @if($current_page < $total_pages)
                                    <span><a href="{{ route('tv.index', array_merge($paginationParams, ['page' => $current_page + 1])) }}">
                                        <i class="ion-arrow-right-b"></i></a></span>
                                @endif
                            </div>
                        </div>
                    @endif
                @else
                    <div class="no-results">
                        <h3>No TV shows found</h3>
                        <p>Try adjusting your filters or check back later for new content.</p>
                    </div>
                @endif
            </div>

            <div class="col-md-4 col-sm-12 col-xs-12" id="sidebar-content">
                <div class="sidebar">
                    <div class="searh-form">
                        <h4 class="sb-title">Search for TV Shows</h4>
                        <form class="form-style-1" method="GET" action="{{ route('tv.search') }}">
                            <div class="row">
                                <div class="col-md-12 form-it">
                                    <label>TV Show name</label>
                                    <input type="text" name="q" placeholder="Enter keywords here" value="{{ request('q') }}">
                                </div>
                                <div class="col-md-12 form-it">
                                    <input class="submit" type="submit" value="Search">
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- TV Series Gamification Section -->
                    <div class="tv-gamification">
                        <h4 class="sb-title">TV Series Challenge</h4>
                        <div class="gamification-card" style="background: #0d1b2a; padding: 18px; border-radius: 5px; border: 1px solid #1e3a5f;">
                            <!-- Daily Quiz Question -->
                            <div class="quiz-section" style="margin-bottom: 18px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                    <h5 style="color: #ffffff; margin: 0; font-size: 13px; font-weight: 600;">
                                        Daily Trivia
                                    </h5>
                                    <span style="background: #1e3a5f; color: #abb7c4; padding: 3px 8px; border-radius: 3px; font-size: 13px;">
                                        +10 Points
                                    </span>
                                </div>
                                <p id="quiz-question" style="color: #abb7c4; font-size: 13px; line-height: 1.5; margin-bottom: 12px;">
                                    Which TV series holds the record for most Emmy Awards won by a comedy series?
                                </p>
                                <div class="quiz-options" style="display: flex; flex-direction: column; gap: 6px;">
                                    <button class="quiz-option" data-answer="wrong" style="background: #020d18; color: #abb7c4; border: 1px solid #1e3a5f; padding: 8px 12px; border-radius: 3px; cursor: pointer; transition: all 0.2s ease; text-align: left; font-size: 13px;">
                                        A) Friends
                                    </button>
                                    <button class="quiz-option" data-answer="correct" style="background: #020d18; color: #abb7c4; border: 1px solid #1e3a5f; padding: 8px 12px; border-radius: 3px; cursor: pointer; transition: all 0.2s ease; text-align: left; font-size: 13px;">
                                        B) Frasier
                                    </button>
                                    <button class="quiz-option" data-answer="wrong" style="background: #020d18; color: #abb7c4; border: 1px solid #1e3a5f; padding: 8px 12px; border-radius: 3px; cursor: pointer; transition: all 0.2s ease; text-align: left; font-size: 13px;">
                                        C) The Office
                                    </button>
                                    <button class="quiz-option" data-answer="wrong" style="background: #020d18; color: #abb7c4; border: 1px solid #1e3a5f; padding: 8px 12px; border-radius: 3px; cursor: pointer; transition: all 0.2s ease; text-align: left; font-size: 13px;">
                                        D) Modern Family
                                    </button>
                                </div>
                                <div id="quiz-result" style="margin-top: 10px; padding: 8px; border-radius: 3px; display: none; font-size: 13px;"></div>
                            </div>

                            <!-- Achievements Badge -->
                            <div class="achievements-section" style="border-top: 1px solid #1e3a5f; padding-top: 15px; margin-bottom: 15px;">
                                <h5 style="color: #ffffff; margin: 0 0 10px 0; font-size: 13px; font-weight: 600;">
                                    Achievements
                                </h5>
                                <div class="badges-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;">
                                    <div class="badge-item" data-badge="binge" style="text-align: center; padding: 8px; background: #020d18; border: 1px solid #1e3a5f; border-radius: 3px; cursor: pointer;">
                                        <div style="font-size: 13px; color: #e9d736; font-weight: 600;">B</div>
                                        <span style="font-size: 8px; color: #abb7c4; display: block; margin-top: 2px;">Binge</span>
                                    </div>
                                    <div class="badge-item" data-badge="critic" style="text-align: center; padding: 8px; background: #020d18; border: 1px solid #1e3a5f; border-radius: 3px; cursor: pointer; opacity: 0.4;">
                                        <div style="font-size: 13px; color: #e9d736; font-weight: 600;">C</div>
                                        <span style="font-size: 8px; color: #abb7c4; display: block; margin-top: 2px;">Critic</span>
                                    </div>
                                    <div class="badge-item" data-badge="explorer" style="text-align: center; padding: 8px; background: #020d18; border: 1px solid #1e3a5f; border-radius: 3px; cursor: pointer; opacity: 0.4;">
                                        <div style="font-size: 13px; color: #e9d736; font-weight: 600;">E</div>
                                        <span style="font-size: 8px; color: #abb7c4; display: block; margin-top: 2px;">Explorer</span>
                                    </div>
                                    <div class="badge-item" data-badge="trivia" style="text-align: center; padding: 8px; background: #020d18; border: 1px solid #1e3a5f; border-radius: 3px; cursor: pointer; opacity: 0.4;">
                                        <div style="font-size: 13px; color: #e9d736; font-weight: 600;">T</div>
                                        <span style="font-size: 8px; color: #abb7c4; display: block; margin-top: 2px;">Trivia</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Stats Display -->
                            <div class="stats-section" style="border-top: 1px solid #1e3a5f; padding-top: 15px;">
                                <div style="display: flex; justify-content: space-around; text-align: center;">
                                    <div>
                                        <div style="color: #e9d736; font-size: 16px; font-weight: 600;" id="user-points">150</div>
                                        <div style="color: #abb7c4; font-size: 13px;">Points</div>
                                    </div>
                                    <div>
                                        <div style="color: #e9d736; font-size: 16px; font-weight: 600;" id="user-streak">5</div>
                                        <div style="color: #abb7c4; font-size: 13px;">Streak</div>
                                    </div>
                                    <div>
                                        <div style="color: #e9d736; font-size: 16px; font-weight: 600;" id="user-rank">#247</div>
                                        <div style="color: #abb7c4; font-size: 13px;">Rank</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @auth
                    <!-- Quick Watchlist Feature -->
                    <div class="quick-watchlist">
                        <h4 class="sb-title">Quick Add to Watchlist</h4>
                        <p style="color: #abb7c4; font-size: 13px; margin-bottom: 15px;">Trending shows you might like</p>
                        @if(!empty($tvShows) && count($tvShows) > 0)
                            @foreach(array_slice($tvShows, 0, 4) as $show)
                                <div class="watchlist-item" style="display: flex; align-items: center; margin-bottom: 15px; padding: 10px; border-radius: 8px; background: #020d18; transition: all 0.3s ease;">
                                    <a href="{{ route('tv.show', $show['id']) }}" style="flex-shrink: 0;">
                                        <img src="{{ $show['poster_url'] ?? asset('images/uploads/movie-placeholder.jpg') }}" 
                                             alt="{{ $show['name'] }}" 
                                             width="60" height="85" 
                                             style="object-fit: cover; border-radius: 5px; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                                    </a>
                                    <div style="flex: 1; margin-left: 12px; min-width: 0;">
                                        <h6 style="margin: 0 0 5px 0; font-size: 13px; line-height: 1.3;">
                                            <a href="{{ route('tv.show', $show['id']) }}" style="color: #ffffff;">
                                                {{ Str::limit($show['name'], 30) }}
                                            </a>
                                        </h6>
                                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                            <span style="color: #e9d736; font-size: 12px;">
                                                <i class="ion-android-star"></i> {{ number_format($show['vote_average'] ?? 0, 1) }}
                                            </span>
                                            @if(!empty($show['first_air_date']))
                                                <span style="color: #abb7c4; font-size: 13px;">
                                                    {{ date('Y', strtotime($show['first_air_date'])) }}
                                                </span>
                                            @endif
                                        </div>
                                        <button class="add-to-watchlist-btn" 
                                                data-show-id="{{ $show['id'] }}"
                                                data-show-name="{{ $show['name'] }}"
                                                data-show-poster="{{ $show['poster_url'] ?? '' }}"
                                                data-show-year="{{ !empty($show['first_air_date']) ? date('Y', strtotime($show['first_air_date'])) : '' }}"
                                                style="background: #ec6eab; color: white; border: none; padding: 6px 10px; border-radius: 3px; font-size: 16px; font-weight: bold; cursor: pointer; transition: all 0.3s ease; width: auto; min-width: 32px;">
                                            +
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <a href="{{ route('user.profile') }}" class="btn" style="margin-top: 10px;">View My Watchlist<i class="ion-ios-arrow-right"></i></a>
                    </div>
                    @else
                    <!-- Login Prompt for Watchlist -->
                    <div class="quick-watchlist">
                        <h4 class="sb-title">Quick Add to Watchlist</h4>
                        <div style="background: #020d18; padding: 20px; border-radius: 8px; text-align: center;">
                            <i class="ion-ios-list-outline" style="font-size: 48px; color: #ec6eab; margin-bottom: 15px;"></i>
                            <p style="color: #abb7c4; font-size: 13px; margin-bottom: 15px;">
                                Sign in to save TV shows to your watchlist and never miss an episode!
                            </p>
                            <a href="#" class="btn loginLink" style="background: #ec6eab; color: white; padding: 10px 20px; border-radius: 20px; text-decoration: none; display: inline-block;">
                                <i class="ion-log-in"></i> Login to Continue
                            </a>
                        </div>
                    </div>
                    @endauth
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

.dropdown-item.active {
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

/* Grid Toggle Styles */
#grid-toggle-btn:hover {
    background: #ec6eab !important;
    color: white !important;
    transform: translateY(-1px);
}

#grid-toggle-btn.active {
    background: #ec6eab !important;
    color: white !important;
}

/* Full Grid Layout */
.full-grid-mode #main-content {
    width: 100% !important;
    flex: 0 0 100% !important;
    max-width: 100% !important;
}

.full-grid-mode #sidebar-content {
    display: none !important;
}

.full-grid-mode .flex-wrap-movielist {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(185px, 1fr)) !important;
    gap: 20px !important;
    justify-items: center !important;
}

.full-grid-mode .movie-item-style-2 {
    width: 185px !important;
    margin-bottom: 0 !important;
}

/* Gamification Section Styling */
.tv-gamification {
    margin-bottom: 30px;
}

.tv-gamification .sb-title {
    margin-bottom: 12px;
}

.gamification-card {
    transition: none;
}

.quiz-option {
    font-family: inherit;
}

.quiz-option:hover:not(:disabled) {
    border-color: #405266 !important;
    background: #0d1b2a !important;
}

.badge-item {
    cursor: pointer;
    transition: opacity 0.2s ease;
}

.badge-item:hover {
    opacity: 0.8 !important;
}

/* Celebrity image styling */
.celebrities .celeb-item img {
    border-radius: 100% !important;
    object-fit: cover !important;
    width: 70px !important;
    height: 70px !important;
}

.celebrities .celeb-item img:hover {
    transform: scale(1.05) !important;
    box-shadow: 0 4px 12px rgba(236, 110, 171, 0.3) !important;
}

.celebrities .celeb-item {
    transition: all 0.3s ease;
}

.celebrities .celeb-item:hover {
    transform: translateY(-2px);
}
</style>

<script>
// Filter dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    // Handle dropdown toggles
    const filterButtons = document.querySelectorAll('.filter-btn');
    const dropdownContents = document.querySelectorAll('.dropdown-content');
    
    // Grid toggle functionality
    const gridToggleBtn = document.getElementById('grid-toggle-btn');
    const mainRow = document.getElementById('main-row');
    let isFullGrid = false;
    
    // Load grid preference from localStorage
    const savedGridMode = localStorage.getItem('tvGridMode');
    if (savedGridMode === 'full') {
        toggleGridMode();
    }
    
    if (gridToggleBtn) {
        gridToggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            toggleGridMode();
        });
    }
    
    function toggleGridMode() {
        isFullGrid = !isFullGrid;
        
        if (isFullGrid) {
            mainRow.classList.add('full-grid-mode');
            gridToggleBtn.classList.add('active');
            gridToggleBtn.querySelector('span').textContent = 'Exit Grid';
            gridToggleBtn.querySelector('i').className = 'ion-arrow-shrink';
            localStorage.setItem('tvGridMode', 'full');
        } else {
            mainRow.classList.remove('full-grid-mode');
            gridToggleBtn.classList.remove('active');
            gridToggleBtn.querySelector('span').textContent = 'Full Grid';
            gridToggleBtn.querySelector('i').className = 'ion-grid';
            localStorage.setItem('tvGridMode', 'normal');
        }
    }
    
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
                const searchUrl = `{{ route('tv.search') }}?q=${encodeURIComponent(query)}`;
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

// Initialize TV show grid functionality
$(document).ready(function() {
    // Quick Add to Watchlist functionality
    $('.add-to-watchlist-btn').on('click', function(e) {
        e.preventDefault();
        const button = $(this);
        const showId = button.data('show-id');
        const showName = button.data('show-name');
        const showPoster = button.data('show-poster');
        const showYear = button.data('show-year');
        
        // Check if user is authenticated
        @guest
            alert('Please login to add shows to your watchlist');
            return;
        @endguest
        
        // Disable button during request
        button.prop('disabled', true);
        const originalHtml = button.html();
        button.html('...');
        
        // Make AJAX request to add to watchlist
        $.ajax({
            url: '{{ route("user.watchlist.add") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                media_id: showId,
                media_type: 'tv',
                title: showName,
                poster: showPoster,
                year: showYear
            },
            success: function(response) {
                button.html('✓');
                button.css('background', '#4CAF50');
                
                // Show success message
                showNotification('success', showName + ' added to your watchlist!');
                
                // Revert button after 2 seconds
                setTimeout(function() {
                    button.html(originalHtml);
                    button.css('background', '#ec6eab');
                    button.prop('disabled', false);
                }, 2000);
            },
            error: function(xhr) {
                let errorMsg = 'Failed to add to watchlist';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                
                button.html(originalHtml);
                button.prop('disabled', false);
                showNotification('error', errorMsg);
            }
        });
    });
    
    // Notification function
    function showNotification(type, message) {
        const notificationClass = type === 'success' ? 'success' : 'error';
        const icon = type === 'success' ? 'ion-checkmark-circled' : 'ion-alert-circled';
        const bgColor = type === 'success' ? '#4CAF50' : '#f44336';
        
        const notification = $('<div>')
            .css({
                'position': 'fixed',
                'top': '20px',
                'right': '20px',
                'background': bgColor,
                'color': 'white',
                'padding': '15px 25px',
                'border-radius': '8px',
                'box-shadow': '0 4px 15px rgba(0,0,0,0.3)',
                'z-index': 9999,
                'display': 'flex',
                'align-items': 'center',
                'gap': '10px',
                'animation': 'slideInRight 0.3s ease'
            })
            .html('<i class="' + icon + '" style="font-size: 20px;"></i>' + message);
        
        $('body').append(notification);
        
        setTimeout(function() {
            notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }
    
    // Hover effects for watchlist items
    $('.watchlist-item').hover(
        function() {
            $(this).css({
                'background': '#0a1929',
                'transform': 'translateX(5px)'
            });
        },
        function() {
            $(this).css({
                'background': '#020d18',
                'transform': 'translateX(0)'
            });
        }
    );

    // ===========================================
    // GAMIFICATION FEATURES
    // ===========================================
    
    // TV Series Quiz Functionality
    let quizAnswered = localStorage.getItem('tvQuizAnswered_' + new Date().toDateString());
    let userPoints = parseInt(localStorage.getItem('tvUserPoints')) || 150;
    let userStreak = parseInt(localStorage.getItem('tvUserStreak')) || 5;
    
    // Update stats display
    $('#user-points').text(userPoints);
    $('#user-streak').text(userStreak);
    
    // Disable quiz if already answered today
    if (quizAnswered === 'true') {
        $('.quiz-option').prop('disabled', true).css({
            'opacity': '0.5',
            'cursor': 'not-allowed'
        });
        $('#quiz-result').show().css({
            'background': '#0a1929',
            'color': '#e9d736',
            'border': '1px solid #e9d736'
        }).html('<i class="ion-checkmark-circled"></i> You\'ve completed today\'s quiz! Come back tomorrow for more.');
    }
    
    // Handle quiz option clicks
    $('.quiz-option').on('click', function() {
        if (quizAnswered === 'true') return;
        
        const button = $(this);
        const answer = button.data('answer');
        const resultDiv = $('#quiz-result');
        
        // Disable all options
        $('.quiz-option').prop('disabled', true).css('pointer-events', 'none');
        
        if (answer === 'correct') {
            button.css({
                'background': '#4CAF50',
                'color': 'white',
                'border-color': '#4CAF50'
            });
            
            // Award points
            userPoints += 10;
            userStreak += 1;
            localStorage.setItem('tvUserPoints', userPoints);
            localStorage.setItem('tvUserStreak', userStreak);
            $('#user-points').text(userPoints);
            $('#user-streak').text(userStreak);
            
            resultDiv.show().css({
                'background': 'rgba(76, 175, 80, 0.1)',
                'color': '#4CAF50',
                'border': '1px solid #4CAF50'
            }).html('Correct! You earned 10 points. Frasier won 37 Emmy Awards!');
            
            // Check for trivia badge unlock
            if (userPoints >= 200) {
                unlockBadge('trivia');
            }
        } else {
            button.css({
                'background': '#f44336',
                'color': 'white',
                'border-color': '#f44336'
            });
            
            // Show correct answer
            $('.quiz-option[data-answer="correct"]').css({
                'background': '#4CAF50',
                'color': 'white',
                'border-color': '#4CAF50'
            });
            
            resultDiv.show().css({
                'background': 'rgba(244, 67, 54, 0.1)',
                'color': '#f44336',
                'border': '1px solid #f44336'
            }).html('Incorrect. The correct answer is Frasier with 37 Emmy Awards.');
        }
        
        // Mark quiz as answered
        localStorage.setItem('tvQuizAnswered_' + new Date().toDateString(), 'true');
    });
    
    // Badge unlock function
    function unlockBadge(badgeType) {
        const badge = $(`.badge-item[data-badge="${badgeType}"]`);
        if (badge.css('opacity') == '0.4') {
            badge.css('opacity', '1');
            
            // Show unlock notification
            const badgeNames = {
                'binge': 'Binge Watcher',
                'critic': 'Top Critic',
                'explorer': 'Genre Explorer',
                'trivia': 'Trivia Master'
            };
            
            showNotification('success', 'Achievement Unlocked: ' + badgeNames[badgeType]);
            localStorage.setItem('tvBadge_' + badgeType, 'unlocked');
        }
    }
    
    // Load unlocked badges
    ['binge', 'critic', 'explorer', 'trivia'].forEach(function(badgeType) {
        if (localStorage.getItem('tvBadge_' + badgeType) === 'unlocked') {
            $(`.badge-item[data-badge="${badgeType}"]`).css('opacity', '1');
        }
    });
});

// Add simple styles
const style = document.createElement('style');
style.textContent = `
    .watchlist-item {
        transition: all 0.2s ease !important;
    }
    
    .add-to-watchlist-btn:hover {
        background: #d5006f !important;
    }
    
    .add-to-watchlist-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
    
    .quiz-option {
        transition: all 0.2s ease !important;
    }
    
    .badge-item {
        transition: opacity 0.2s ease !important;
    }
`;
document.head.appendChild(style);
</script>
@endsection
