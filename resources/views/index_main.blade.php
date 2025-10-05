@extends('layouts.app')

@section('title', 'Cinema Paradiso - Home')

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

@section('content')

<!-- THIS IS INDEX_MAIN.BLADE.PHP - FULL MOVIE LAYOUT -->
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

<div class="slider movie-items">
	<div class="container">
		<!-- User Experience Taglines Section - Overlaid on slider -->
		<div class="user-experience-section">
			<div class="tagline-container">
				<div class="animated-tagline" id="taglineText">
					<!-- Dynamic text will be inserted here -->
				</div>
			</div>
		</div>
		
		<div class="row">
	    	<div class="slick-multiItemSlider">
	    		@if($error)
	    			{{-- Show fallback content when API is unavailable --}}
	    			<div class="movie-item">
		    			<div class="mv-img">
		    				<a href="#"><img src="{{ asset('images/uploads/slider1.jpg') }}" alt="" width="285" height="437"></a>
		    			</div>
		    			<div class="title-in">
		    				<div class="cate">
		    					<span class="blue"><a href="#">Sci-fi</a></span>
		    				</div>
		    				<h6><a href="#">Featured Movies</a></h6>
		    				<p><i class="ion-android-star"></i><span>8.0</span> /10</p>
		    			</div>
		    		</div>
		    		<div class="movie-item">
		    			<div class="mv-img">
		    				<a href="#"><img src="{{ asset('images/uploads/slider2.jpg') }}" alt="" width="285" height="437"></a>
		    			</div>
		    			<div class="title-in">
		    				<div class="cate">
		    					<span class="yell"><a href="#">Action</a></span>
		    				</div>
		    				<h6><a href="#">Coming Soon</a></h6>
		    				<p><i class="ion-android-star"></i><span>7.5</span> /10</p>
		    			</div>
		    		</div>
	    		@else
	    			@forelse($trending as $movie)
	    				@include('partials._movieCard', ['movie' => $movie, 'genres' => $genres])
	    			@empty
	    				{{-- Fallback if no trending movies --}}
	    				<div class="movie-item">
			    			<div class="mv-img">
			    				<a href="{{ route('movies.index') }}"><img src="{{ asset('images/uploads/slider1.jpg') }}" alt="" width="285" height="437"></a>
			    			</div>
			    			<div class="title-in">
			    				<div class="cate">
			    					<span class="blue"><a href="{{ route('movies.index') }}">Movies</a></span>
			    				</div>
			    				<h6><a href="{{ route('movies.index') }}">Explore Movies</a></h6>
			    				<p><i class="ion-android-star"></i><span>--</span> /10</p>
			    			</div>
			    		</div>
	    			@endforelse
	    		@endif
	    	</div>
	    </div>
	</div>
</div>
<div class="movie-items">
	<div class="container">
		<div class="row ipad-width">
			<div class="col-md-8">
				<div class="title-hd">
					<h2>Movies</h2>
					<a href="{{ route('movies.index') }}" class="viewall">View all <i class="ion-ios-arrow-right"></i></a>
				</div>
				<div class="tabs">
					<ul class="tab-links">
						<li class="active"><a href="#tab1"> Popular</a></li>
						<li><a href="#tab2"> Upcoming</a></li>
						<li><a href="#tab3"> Top rated  </a></li>
						<li><a href="#tab4"> Trending</a></li>                        
					</ul>
				    <div class="tab-content">
				        <div id="tab1" class="tab active">
				            <div class="row">
				            	<div class="slick-multiItem">
				            		@if($error)
				            			{{-- Fallback content when API is unavailable --}}
				            			<div class="slide-it">
					            			<div class="movie-item">
						            			<div class="mv-img">
						            				<img src="{{ asset('images/uploads/mv-item1.jpg') }}" alt="" width="185" height="284">
						            			</div>
						            			<div class="hvr-inner">
						            				<a href="{{ route('movies.index') }}">Browse Movies <i class="ion-android-arrow-dropright"></i></a>
						            			</div>
						            			<div class="title-in">
						            				<h6><a href="{{ route('movies.index') }}">Explore Movies</a></h6>
						            				<p><i class="ion-android-star"></i><span>--</span> /10</p>
						            			</div>
						            		</div>
					            		</div>
				            		@else
				            			@forelse(array_slice($popular, 0, 8) as $movie)
				            				@include('partials._movieCardSmall', ['movie' => $movie])
				            			@empty
				            				<div class="slide-it">
						            			<div class="movie-item">
							            			<div class="mv-img">
							            				<img src="{{ asset('images/uploads/mv-item1.jpg') }}" alt="" width="185" height="284">
							            			</div>
							            			<div class="hvr-inner">
							            				<a href="{{ route('movies.index') }}">Browse Movies <i class="ion-android-arrow-dropright"></i></a>
							            			</div>
							            			<div class="title-in">
							            				<h6><a href="{{ route('movies.index') }}">Popular Movies</a></h6>
							            				<p><i class="ion-android-star"></i><span>--</span> /10</p>
							            			</div>
							            		</div>
						            		</div>
				            			@endforelse
				            		@endif
				            	</div>
				            </div>
				        </div>
				        <div id="tab2" class="tab">
				           <div class="row">
				            	<div class="slick-multiItem">
				            		@if($error)
				            			{{-- Fallback content when API is unavailable --}}
				            			<div class="slide-it">
					            			<div class="movie-item">
						            			<div class="mv-img">
						            				<img src="{{ asset('images/uploads/mv-item2.jpg') }}" alt="" width="185" height="284">
						            			</div>
						            			<div class="hvr-inner">
						            				<a href="{{ route('movies.index') }}">Browse Movies <i class="ion-android-arrow-dropright"></i></a>
						            			</div>
						            			<div class="title-in">
						            				<h6><a href="{{ route('movies.index') }}">Coming Soon</a></h6>
						            				<p><i class="ion-android-star"></i><span>--</span> /10</p>
						            			</div>
						            		</div>
					            		</div>
				            		@else
				            			@forelse(array_slice($upcoming, 0, 8) as $movie)
				            				@include('partials._movieCardSmall', ['movie' => $movie])
				            			@empty
				            				<div class="slide-it">
						            			<div class="movie-item">
							            			<div class="mv-img">
							            				<img src="{{ asset('images/uploads/mv-item2.jpg') }}" alt="" width="185" height="284">
							            			</div>
							            			<div class="hvr-inner">
							            				<a href="{{ route('movies.index', ['category' => 'upcoming']) }}">Browse Upcoming <i class="ion-android-arrow-dropright"></i></a>
							            			</div>
							            			<div class="title-in">
							            				<h6><a href="{{ route('movies.index', ['category' => 'upcoming']) }}">Upcoming Movies</a></h6>
							            				<p><i class="ion-android-star"></i><span>--</span> /10</p>
							            			</div>
							            		</div>
						            		</div>
				            			@endforelse
				            		@endif
				            	</div>
				            </div>
				        </div>
				        <div id="tab3" class="tab">
				        	<div class="row">
				            	<div class="slick-multiItem">
				            		@if($error)
				            			{{-- Fallback content when API is unavailable --}}
				            			<div class="slide-it">
					            			<div class="movie-item">
						            			<div class="mv-img">
						            				<img src="{{ asset('images/uploads/mv-item3.jpg') }}" alt="" width="185" height="284">
						            			</div>
						            			<div class="hvr-inner">
						            				<a href="{{ route('movies.index') }}">Browse Movies <i class="ion-android-arrow-dropright"></i></a>
						            			</div>
						            			<div class="title-in">
						            				<h6><a href="{{ route('movies.index') }}">Top Rated</a></h6>
						            				<p><i class="ion-android-star"></i><span>--</span> /10</p>
						            			</div>
						            		</div>
					            		</div>
				            		@else
				            			@forelse(array_slice($topRated, 0, 8) as $movie)
				            				@include('partials._movieCardSmall', ['movie' => $movie])
				            			@empty
				            				<div class="slide-it">
						            			<div class="movie-item">
							            			<div class="mv-img">
							            				<img src="{{ asset('images/uploads/mv-item3.jpg') }}" alt="" width="185" height="284">
							            			</div>
							            			<div class="hvr-inner">
							            				<a href="{{ route('movies.index', ['category' => 'top-rated']) }}">Browse Top Rated <i class="ion-android-arrow-dropright"></i></a>
							            			</div>
							            			<div class="title-in">
							            				<h6><a href="{{ route('movies.index', ['category' => 'top-rated']) }}">Top Rated Movies</a></h6>
							            				<p><i class="ion-android-star"></i><span>--</span> /10</p>
							            			</div>
							            		</div>
						            		</div>
				            			@endforelse
				            		@endif
				            	</div>
				            </div>
			       	 	</div>
			       	 	<div id="tab4" class="tab">
				        	<div class="row">
				            	<div class="slick-multiItem">
				            		@if($error)
				            			{{-- Fallback content when API is unavailable --}}
				            			<div class="slide-it">
					            			<div class="movie-item">
						            			<div class="mv-img">
						            				<img src="{{ asset('images/uploads/mv-item4.jpg') }}" alt="" width="185" height="284">
						            			</div>
						            			<div class="hvr-inner">
						            				<a href="{{ route('movies.index') }}">Browse Movies <i class="ion-android-arrow-dropright"></i></a>
						            			</div>
						            			<div class="title-in">
						            				<h6><a href="{{ route('movies.index') }}">Trending</a></h6>
						            				<p><i class="ion-android-star"></i><span>--</span> /10</p>
						            			</div>
						            		</div>
					            		</div>
				            		@else
				            			@forelse(array_slice($trending, 0, 8) as $movie)
				            				@include('partials._movieCardSmall', ['movie' => $movie])
				            			@empty
				            				<div class="slide-it">
						            			<div class="movie-item">
							            			<div class="mv-img">
							            				<img src="{{ asset('images/uploads/mv-item4.jpg') }}" alt="" width="185" height="284">
							            			</div>
							            			<div class="hvr-inner">
							            				<a href="{{ route('movies.index', ['category' => 'trending']) }}">Browse Trending <i class="ion-android-arrow-dropright"></i></a>
							            			</div>
							            			<div class="title-in">
							            				<h6><a href="{{ route('movies.index', ['category' => 'trending']) }}">Trending Movies</a></h6>
							            				<p><i class="ion-android-star"></i><span>--</span> /10</p>
							            			</div>
							            		</div>
						            		</div>
				            			@endforelse
				            		@endif
				            	</div>
				            </div>
			       	 	</div>
				    </div>
				</div>
				<div class="title-hd">
					<h2>on tv</h2>
					<a href="{{ route('tv.index') }}" class="viewall">View all <i class="ion-ios-arrow-right"></i></a>
				</div>
				<div class="tabs">
					<ul class="tab-links-2">
						<li class="active"><a href="#tab21">#Popular</a></li>
						<li><a href="#tab22"> #Airing Today</a></li>
						<li><a href="#tab23">  #Top rated  </a></li>
						<li><a href="#tab24"> #On The Air</a></li>                        
					</ul>
				    <div class="tab-content">
				        <div id="tab21" class="tab active">
				            <div class="row">
				            	<div class="slick-multiItem">
				            		@forelse($popularTVShows ?? [] as $show)
				            		<div class="slide-it">
				            			<div class="movie-item">
					            			<div class="mv-img">
					            				<img src="{{ $show['poster_url'] ?? asset('images/uploads/movie-placeholder.jpg') }}" alt="{{ $show['name'] ?? 'TV Show' }}" width="185" height="284">
					            			</div>
					            			<div class="hvr-inner">
					            				<a href="{{ route('tv.show', $show['id']) }}"> Read more <i class="ion-android-arrow-dropright"></i> </a>
					            			</div>
					            			<div class="title-in">
					            				<h6><a href="{{ route('tv.show', $show['id']) }}">{{ Str::limit($show['name'] ?? 'TV Show', 20) }}</a></h6>
					            				<p><i class="ion-android-star"></i><span>{{ number_format($show['vote_average'] ?? 0, 1) }}</span> /10</p>
					            			</div>
					            		</div>
				            		</div>
				            		@empty
				            		<div class="slide-it">
				            			<div class="movie-item">
					            			<div class="mv-img">
					            				<img src="{{ asset('images/uploads/movie-placeholder.jpg') }}" alt="" width="185" height="284">
					            			</div>
					            			<div class="hvr-inner">
					            				<a href="#"> No TV shows available <i class="ion-android-arrow-dropright"></i> </a>
					            			</div>
					            			<div class="title-in">
					            				<h6><a href="#">No TV Shows</a></h6>
					            				<p><i class="ion-android-star"></i><span>--</span> /10</p>
					            			</div>
					            		</div>
				            		</div>
				            		@endforelse
				            	</div>
				            </div>
				        </div>
				        <div id="tab22" class="tab">
				           <div class="row">
				            	<div class="slick-multiItem">
				            		@forelse($airingTodayTVShows ?? [] as $show)
				            		<div class="slide-it">
				            			<div class="movie-item">
					            			<div class="mv-img">
					            				<img src="{{ $show['poster_url'] ?? asset('images/uploads/movie-placeholder.jpg') }}" alt="{{ $show['name'] ?? 'TV Show' }}" width="185" height="284">
					            			</div>
					            			<div class="hvr-inner">
					            				<a href="{{ route('tv.show', $show['id']) }}"> Read more <i class="ion-android-arrow-dropright"></i> </a>
					            			</div>
					            			<div class="title-in">
					            				<h6><a href="{{ route('tv.show', $show['id']) }}">{{ Str::limit($show['name'] ?? 'TV Show', 20) }}</a></h6>
					            				<p><i class="ion-android-star"></i><span>{{ number_format($show['vote_average'] ?? 0, 1) }}</span> /10</p>
					            			</div>
					            		</div>
				            		</div>
				            		@empty
				            		<div class="slide-it">
				            			<div class="movie-item">
					            			<div class="mv-img">
					            				<img src="{{ asset('images/uploads/movie-placeholder.jpg') }}" alt="" width="185" height="284">
					            			</div>
					            			<div class="hvr-inner">
					            				<a href="#"> No TV shows available <i class="ion-android-arrow-dropright"></i> </a>
					            			</div>
					            			<div class="title-in">
					            				<h6><a href="#">No TV Shows</a></h6>
					            				<p><i class="ion-android-star"></i><span>--</span> /10</p>
					            			</div>
					            		</div>
				            		</div>
				            		@endforelse
				            	</div>
				            </div>
				        </div>
				        <div id="tab23" class="tab">
				        	<div class="row">
				            	<div class="slick-multiItem">
				            		@forelse($topRatedTVShows ?? [] as $show)
				            		<div class="slide-it">
				            			<div class="movie-item">
					            			<div class="mv-img">
					            				<img src="{{ $show['poster_url'] ?? asset('images/uploads/movie-placeholder.jpg') }}" alt="{{ $show['name'] ?? 'TV Show' }}" width="185" height="284">
					            			</div>
					            			<div class="hvr-inner">
					            				<a href="{{ route('tv.show', $show['id']) }}"> Read more <i class="ion-android-arrow-dropright"></i> </a>
					            			</div>
					            			<div class="title-in">
					            				<h6><a href="{{ route('tv.show', $show['id']) }}">{{ Str::limit($show['name'] ?? 'TV Show', 20) }}</a></h6>
					            				<p><i class="ion-android-star"></i><span>{{ number_format($show['vote_average'] ?? 0, 1) }}</span> /10</p>
					            			</div>
					            		</div>
				            		</div>
				            		@empty
				            		<div class="slide-it">
				            			<div class="movie-item">
					            			<div class="mv-img">
					            				<img src="{{ asset('images/uploads/movie-placeholder.jpg') }}" alt="" width="185" height="284">
					            			</div>
					            			<div class="hvr-inner">
					            				<a href="#"> No TV shows available <i class="ion-android-arrow-dropright"></i> </a>
					            			</div>
					            			<div class="title-in">
					            				<h6><a href="#">No TV Shows</a></h6>
					            				<p><i class="ion-android-star"></i><span>--</span> /10</p>
					            			</div>
					            		</div>
				            		</div>
				            		@endforelse
				            	</div>
				            </div>
			       	 	</div>
			       	 	 <div id="tab24" class="tab">
				        	<div class="row">
				            	<div class="slick-multiItem">
				            		@forelse($onTheAirTVShows ?? [] as $show)
				            		<div class="slide-it">
				            			<div class="movie-item">
					            			<div class="mv-img">
					            				<img src="{{ $show['poster_url'] ?? asset('images/uploads/movie-placeholder.jpg') }}" alt="{{ $show['name'] ?? 'TV Show' }}" width="185" height="284">
					            			</div>
					            			<div class="hvr-inner">
					            				<a href="{{ route('tv.show', $show['id']) }}"> Read more <i class="ion-android-arrow-dropright"></i> </a>
					            			</div>
					            			<div class="title-in">
					            				<h6><a href="{{ route('tv.show', $show['id']) }}">{{ Str::limit($show['name'] ?? 'TV Show', 20) }}</a></h6>
					            				<p><i class="ion-android-star"></i><span>{{ number_format($show['vote_average'] ?? 0, 1) }}</span> /10</p>
					            			</div>
					            		</div>
				            		</div>
				            		@empty
				            		<div class="slide-it">
				            			<div class="movie-item">
					            			<div class="mv-img">
					            				<img src="{{ asset('images/uploads/movie-placeholder.jpg') }}" alt="" width="185" height="284">
					            			</div>
					            			<div class="hvr-inner">
					            				<a href="#"> No TV shows available <i class="ion-android-arrow-dropright"></i> </a>
					            			</div>
					            			<div class="title-in">
					            				<h6><a href="#">No TV Shows</a></h6>
					            				<p><i class="ion-android-star"></i><span>--</span> /10</p>
					            			</div>
					            		</div>
				            		</div>
				            		@endforelse
				            	</div>
				            </div>
			       	 	</div>
				    </div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="sidebar">
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
					<div class="celebrities">
						<h4 class="sb-title">Spotlight Celebrities</h4>
						<div class="celeb-item">
							<a href="#"><img src="{{ asset('images/uploads/ava1.jpg') }}" alt="" width="70" height="70"></a>
							<div class="celeb-author">
								<h6><a href="#">Samuel N. Jack</a></h6>
								<span>Actor</span>
							</div>
						</div>
						<div class="celeb-item">
							<a href="#"><img src="{{ asset('images/uploads/ava2.jpg') }}" alt="" width="70" height="70"></a>
							<div class="celeb-author">
								<h6><a href="#">Benjamin Carroll</a></h6>
								<span>Actor</span>
							</div>
						</div>
						<div class="celeb-item">
							<a href="#"><img src="{{ asset('images/uploads/ava3.jpg') }}" alt="" width="70" height="70"></a>
							<div class="celeb-author">
								<h6><a href="#">Beverly Griffin</a></h6>
								<span>Actor</span>
							</div>
						</div>
						<div class="celeb-item">
							<a href="#"><img src="{{ asset('images/uploads/ava4.jpg') }}" alt="" width="70" height="70"></a>
							<div class="celeb-author">
								<h6><a href="#">Justin Weaver</a></h6>
								<span>Actor</span>
							</div>
						</div>
						<a href="#" class="btn">See all celebrities<i class="ion-ios-arrow-right"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="trailers">
	<div class="container">
		<div class="row ipad-width">
			<div class="col-md-12">
				<div class="title-hd">
					<h2>in theater</h2>
					<a href="#" class="viewall">View all <i class="ion-ios-arrow-right"></i></a>
				</div>
				<div class="videos">
				 	<div class="slider-for-2 video-ft">
						@if(!empty($inTheaterTrailers))
							@foreach($inTheaterTrailers as $trailer)
								<div>
									<iframe class="item-video" src="" data-src="{{ $trailer['video_url'] }}"></iframe>
								</div>
							@endforeach
						@else
							{{-- Fallback content if no trailers available --}}
							<div>
								<iframe class="item-video" src="" data-src="https://www.youtube.com/embed/1Q8fG0TtVAY"></iframe>
							</div>
							<div>
								<iframe class="item-video" src="" data-src="https://www.youtube.com/embed/w0qQkSuWOS8"></iframe>
							</div>
						@endif
					</div>
					<div class="slider-nav-2 thumb-ft">
						@if(!empty($inTheaterTrailers))
							@foreach($inTheaterTrailers as $trailer)
								<div class="item">
									<div class="trailer-img">
										<img src="{{ $trailer['backdrop_url'] }}" alt="{{ $trailer['title'] }}" width="350" height="200">
									</div>
									<div class="trailer-infor">
										<h4 class="desc">{{ $trailer['title'] }}</h4>
										<p>{{ $trailer['duration'] }}</p>
									</div>
								</div>
							@endforeach
						@else
							{{-- Fallback content --}}
							<div class="item">
								<div class="trailer-img">
									<img src="{{ asset('images/uploads/trailer7.jpg') }}" alt="Wonder Woman" width="350" height="200">
								</div>
								<div class="trailer-infor">
									<h4 class="desc">Wonder Woman</h4>
									<p>2:30</p>
								</div>
							</div>
							<div class="item">
								<div class="trailer-img">
									<img src="{{ asset('images/uploads/trailer2.jpg') }}" alt="Oblivion" width="350" height="200">
								</div>
								<div class="trailer-infor">
									<h4 class="desc">Oblivion: Official Teaser Trailer</h4>
									<p>2:37</p>
								</div>
							</div>
						@endif
					</div>
				</div>   
			</div>
		</div>
	</div>
</div>
<!-- latest new v1 section-->
<div class="latestnew">
	<div class="container">
		<div class="row ipad-width">
			<div class="col-md-8">
				<div class="ads">
					<img src="{{ asset('images/uploads/ads2.png') }}" alt="" width="728" height="106">
				</div>
				<div class="title-hd">
					<h2>Latest news</h2>
				</div>
				<div class="tabs">
					<ul class="tab-links-3">
						<li class="active"><a href="#tab31">#Movies </a></li>
						<li><a href="#tab32"> #TV Shows </a></li>              
						<li><a href="#tab33">  # Celebs</a></li>                       
					</ul>
				    <div class="tab-content">
				        <div id="tab31" class="tab active">
				            <div class="row">
				            	<div class="blog-item-style-1">
				            		<img src="{{ asset('images/uploads/blog-it1.jpg') }}" alt="" width="170" height="250">
				            		<div class="blog-it-infor">
				            			<h3><a href="#">Brie Larson to play first female white house candidate Victoria Woodull in Amazon film</a></h3>
				            			<span class="time">13 hours ago</span>
				            			<p>Exclusive: <span>Amazon Studios </span>has acquired Victoria Woodhull, with Oscar winning Room star <span>Brie Larson</span> polsed to produce, and play the first female candidate for the presidency of the United States. Amazon bought it in a pitch package deal. <span> Ben Kopit</span>, who wrote the Warner Bros film <span>Libertine</span> that has...</p>
				            		</div>
				            	</div>
				            </div>
				        </div>
				        <div id="tab32" class="tab">
				           <div class="row">
				            	<div class="blog-item-style-1">
				            		<img src="{{ asset('images/uploads/blog-it2.jpg') }}" alt="" width="170" height="250">
				            		<div class="blog-it-infor">
				            			<h3><a href="#">Tab 2</a></h3>
				            			<span class="time">13 hours ago</span>
				            			<p>Exclusive: <span>Amazon Studios </span>has acquired Victoria Woodhull, with Oscar winning Room star <span>Brie Larson</span> polsed to produce, and play the first female candidate for the presidency of the United States. Amazon bought it in a pitch package deal. <span> Ben Kopit</span>, who wrote the Warner Bros film <span>Libertine</span> that has...</p>
				            		</div>
				            	</div>
				            </div>
				        </div>
				        <div id="tab33" class="tab">
				        	<div class="row">
				            	<div class="blog-item-style-1">
				            		<img src="{{ asset('images/uploads/blog-it1.jpg') }}" alt="" width="170" height="250">
				            		<div class="blog-it-infor">
				            			<h3><a href="#">Tab 3</a></h3>
				            			<span class="time">13 hours ago</span>
				            			<p>Exclusive: <span>Amazon Studios </span>has acquired Victoria Woodhull, with Oscar winning Room star <span>Brie Larson</span> polsed to produce, and play the first female candidate for the presidency of the United States. Amazon bought it in a pitch package deal. <span> Ben Kopit</span>, who wrote the Warner Bros film <span>Libertine</span> that has...</p>
				            		</div>
				            	</div>
				            </div>
			       	 	</div>
				    </div>
				</div>
				<div class="morenew">
					<div class="title-hd">
						<h3>More news on Blockbuster</h3>
						<a href="#" class="viewall">See all Movies news<i class="ion-ios-arrow-right"></i></a>
					</div>
					<div class="more-items">
						<div class="left">
							<div class="more-it">
								<h6><a href="#">Michael Shannon Frontrunner to play Cable in “Deadpool 2”</a></h6>
								<span class="time">13 hours ago</span>
							</div>
							<div class="more-it">
								<h6><a href="#">French cannibal horror “Raw” inspires L.A. theater to hand out “Barf Bags”</a></h6>
								
								<span class="time">13 hours ago</span>
							</div>
						</div>
						<div class="right">
							<div class="more-it">
								<h6><a href="#">Laura Dern in talks to join Justin Kelly’s biopic “JT Leroy”</a></h6>
								<span class="time">13 hours ago</span>
							</div>
							<div class="more-it">
								<h6><a href="#">China punishes more than 300 cinemas for box office cheating</a></h6>
								<span class="time">13 hours ago</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="sidebar">
					<div class="sb-mail sb-it">
						<h4 class="sb-title">Contact Us</h4>
						<div class="contact-info">
							<div class="contact-item">
								<i class="ion-android-mail"></i>
								<div class="contact-details">
									<h6>Email us</h6>
									<a href="mailto:ttawhid401@gmail.com">ttawhid401@gmail.com</a>
								</div>
							</div>
							<p class="contact-note">We'd love to hear your feedback and suggestions!</p>
						</div>					
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--end of latest new v1 section-->
<!-- footer section-->
<footer class="ht-footer">
	<div class="container">
		<div class="flex-parent-ft">
			<div class="flex-child-ft item1">
				 <a href="{{ route('home') }}"><img class="logo" src="{{ asset('images/cinema_paradiso.png') }}" alt=""></a>
				 <p>KUET,Khulna,<br>
				Bangladesh</p>
				<p>Call us: <a href="#">+8801326503869</a></p>
			</div>
			<div class="flex-child-ft item2">
				<h4>Resources</h4>
				<ul>
					<li><a href="#">About</a></li> 
					<li><a href="#">Blockbuster</a></li>
					<li><a href="#">Contact Us</a></li>
					<li><a href="#">Forums</a></li>
					<li><a href="#">Blog</a></li>
					<li><a href="#">Help Center</a></li>
				</ul>
			</div>
			<div class="flex-child-ft item3">
				<h4>Legal</h4>
				<ul>
					<li><a href="#">Terms of Use</a></li> 
					<li><a href="#">Privacy Policy</a></li>	
					<li><a href="#">Security</a></li>
				</ul>
			</div>
			<div class="flex-child-ft item4">
				<h4>Account</h4>
				<ul>
					<li><a href="#">My Account</a></li> 
					<li><a href="#">Watchlist</a></li>	
					<li><a href="#">Collections</a></li>
					<li><a href="#">User Guide</a></li>
				</ul>
			</div>
			<div class="flex-child-ft item5">
				<h4>Newsletter</h4>
				<p>Subscribe to our newsletter system now <br> to get latest news from us.</p>
				<form action="#">
					<input type="text" placeholder="Enter your email...">
				</form>
				<a href="#" class="btn">Subscribe now <i class="ion-ios-arrow-forward"></i></a>
			</div>
		</div>
	</div>
	<div class="ft-copyright">
		<div class="ft-left">
			<p>© 2017 Blockbuster. All Rights Reserved. Designed by leehari.</p>
		</div>
		<div class="backtotop">
			<p><a href="#" id="back-to-top">Back to top  <i class="ion-ios-arrow-thin-up"></i></a></p>
		</div>
	</div>
</footer>
<!-- end of footer section-->

@endsection

@push('scripts')
<script>
// Animated Taglines
document.addEventListener('DOMContentLoaded', function() {
    const taglines = [
        "Track your <span class='highlight'>watched list</span>",
        "<span class='highlight'>Save</span> films for later",
        "Recommend movies to <span class='highlight'>friends</span>",
        "Discover what to <span class='highlight'>watch next</span>",
        "Keep your <span class='highlight'>cinema diary</span>",
        "Follow friends' <span class='highlight'>favorites</span>",
        "Never forget what you've <span class='highlight'>watched</span>",
        "Build your personal <span class='highlight'>film collection</span>",
        "Find and share <span class='highlight'>hidden gems</span>",
        "Plan your next <span class='highlight'>movie night</span>"
    ];
    
    const taglineElement = document.getElementById('taglineText');
    let currentIndex = 0;
    
    function changeTagline() {
        // Fade out first
        taglineElement.style.opacity = '0';
        taglineElement.style.transform = 'translateY(20px) scale(0.95)';
        
        // Change text after fade out completes
        setTimeout(() => {
            taglineElement.innerHTML = taglines[currentIndex];
            currentIndex = (currentIndex + 1) % taglines.length;
            
            // Fade in with new text
            taglineElement.style.opacity = '1';
            taglineElement.style.transform = 'translateY(0) scale(1)';
        }, 500); // Wait for fade out
    }
    
    // Set initial tagline
    taglineElement.innerHTML = taglines[currentIndex];
    currentIndex = (currentIndex + 1) % taglines.length;
    
    // Change tagline every 5 seconds
    setInterval(changeTagline, 5000);
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
