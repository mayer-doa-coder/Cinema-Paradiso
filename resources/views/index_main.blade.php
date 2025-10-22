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

/* Maintai				        <div id="tab33" class="tab">
				        	<div class="row">
							@if(isset($latestNews) && count($latestNews) > 2)
								@php $celebNews = array_slice($latestNews, 2, 1); @endphp
								@foreach($celebNews as $news)
								<div class="blog-item-style-1">
									@if(isset($news['image']) && $news['image'])
									<img src="{{ $news['image'] }}" alt="{{ $news['title'] ?? 'Celebrity News' }}" width="170" height="250" style="object-fit: cover;">
									@else
									<img src="{{ asset('images/uploads/ceb1.jpg') }}" alt="Celebrity News" width="170" height="250">
									@endif
									<div class="blog-it-infor">
										<h3>
											@if(isset($news['url']))
											<a href="{{ $news['url'] }}" target="_blank">{{ $news['title'] ?? 'Latest Celebrity News' }}</a>
											@else
											<a href="#">{{ $news['title'] ?? 'Latest Celebrity News' }}</a>
											@endif
										</h3>
										<span class="time">
											{{ isset($news['published_at']) ? \Carbon\Carbon::parse($news['published_at'])->diffForHumans() : (isset($news['pubDate']) ? \Carbon\Carbon::parse($news['pubDate'])->diffForHumans() : '13 hours ago') }}
										</span>
										<p>{{ Str::limit($news['description'] ?? $news['summary'] ?? 'Follow the latest celebrity news, red carpet events, and exclusive interviews with Hollywood stars. Get insider access to entertainment industry personalities and their upcoming projects.', 200) }}</p>
									</div>
								</div>
								@endforeach
							@else
							<div class="blog-item-style-1">
								<img src="{{ asset('images/uploads/blog-it1.jpg') }}" alt="" width="170" height="250">
								<div class="blog-it-infor">
									<h3><a href="#">Celebrity Spotlight</a></h3>
									<span class="time">13 hours ago</span>
									<p>Exclusive: <span>Hollywood Stars</span> continue to captivate audiences with their latest projects and red carpet appearances. Follow the latest celebrity interviews, behind-the-scenes moments, and entertainment industry insights.</p>
								</div>
							</div>
							@endif
						</div>
				</div>cts */
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

/* Celebrity image styling */
.celebrities .celeb-item img {
    border-radius: 100% !important;
    object-fit: cover !important;
    transition: all 0.3s ease !important;
    width: 70px !important;
    height: 70px !important;
}

.celebrities .celeb-item img:hover {
    transform: scale(1.05) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2) !important;
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
		    		</div>
		    		<div class="movie-item">
		    			<div class="mv-img">
		    				<a href="#"><img src="{{ asset('images/uploads/slider2.jpg') }}" alt="" width="285" height="437"></a>
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
						<li class="active"><a href="#tab21">Popular</a></li>
						<li><a href="#tab22"> Airing Today</a></li>
						<li><a href="#tab23"> Top rated  </a></li>
						<li><a href="#tab24"> On The Air</a></li>                        
					</ul>
				    <div class="tab-content">
				        <div id="tab21" class="tab active">
				            <div class="row">
				            	<div class="slick-multiItem">
				            		@forelse($popularTVShows ?? [] as $show)
				            		<div class="slide-it">
				            			<div class="movie-item">
					            			<div class="mv-img">
					            				<a href="{{ route('tv.show', $show['id']) }}">
					            					<img src="{{ $show['poster_url'] ?? asset('images/uploads/movie-placeholder.jpg') }}" alt="{{ $show['name'] ?? 'TV Show' }}" width="185" height="284">
					            				</a>
					            			</div>
					            		</div>
				            		</div>
				            		@empty
				            		<div class="slide-it">
				            			<div class="movie-item">
					            			<div class="mv-img">
					            				<a href="#">
					            					<img src="{{ asset('images/uploads/movie-placeholder.jpg') }}" alt="" width="185" height="284">
					            				</a>
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
					            				<a href="{{ route('tv.show', $show['id']) }}">
					            					<img src="{{ $show['poster_url'] ?? asset('images/uploads/movie-placeholder.jpg') }}" alt="{{ $show['name'] ?? 'TV Show' }}" width="185" height="284">
					            				</a>
					            			</div>
					            		</div>
				            		</div>
				            		@empty
				            		<div class="slide-it">
				            			<div class="movie-item">
					            			<div class="mv-img">
					            				<a href="#">
					            					<img src="{{ asset('images/uploads/movie-placeholder.jpg') }}" alt="" width="185" height="284">
					            				</a>
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
					            				<a href="{{ route('tv.show', $show['id']) }}">
					            					<img src="{{ $show['poster_url'] ?? asset('images/uploads/movie-placeholder.jpg') }}" alt="{{ $show['name'] ?? 'TV Show' }}" width="185" height="284">
					            				</a>
					            			</div>
					            		</div>
				            		</div>
				            		@empty
				            		<div class="slide-it">
				            			<div class="movie-item">
					            			<div class="mv-img">
					            				<a href="#">
					            					<img src="{{ asset('images/uploads/movie-placeholder.jpg') }}" alt="" width="185" height="284">
					            				</a>
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
					            				<a href="{{ route('tv.show', $show['id']) }}">
					            					<img src="{{ $show['poster_url'] ?? asset('images/uploads/movie-placeholder.jpg') }}" alt="{{ $show['name'] ?? 'TV Show' }}" width="185" height="284">
					            				</a>
					            			</div>
					            		</div>
				            		</div>
				            		@empty
				            		<div class="slide-it">
				            			<div class="movie-item">
					            			<div class="mv-img">
					            				<a href="#">
					            					<img src="{{ asset('images/uploads/movie-placeholder.jpg') }}" alt="" width="185" height="284">
					            				</a>
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
						@if(!empty($spotlightCelebrities))
							@foreach($spotlightCelebrities as $celebrity)
								<div class="celeb-item">
									<a href="{{ route('celebrities.show', $celebrity['id']) }}">
										<img src="{{ $celebrity['profile_url'] ?? asset('images/uploads/ava1.jpg') }}" 
											 alt="{{ $celebrity['name'] }}" 
											 width="70" height="70" 
											 style="border-radius: 100%; object-fit: cover; width: 70px; height: 70px;">
									</a>
									<div class="celeb-author">
										<h6><a href="{{ route('celebrities.show', $celebrity['id']) }}">{{ $celebrity['name'] }}</a></h6>
										<span>{{ $celebrity['known_for_department'] }}</span>
									</div>
								</div>
							@endforeach
						@else
							<!-- Fallback content if no celebrities data -->
							<div class="celeb-item">
								<a href="{{ route('celebrities') }}">
									<img src="{{ asset('images/uploads/ava1.jpg') }}" alt="" width="70" height="70" 
										 style="border-radius: 100%; object-fit: cover; width: 70px; height: 70px;">
								</a>
								<div class="celeb-author">
									<h6><a href="{{ route('celebrities') }}">Browse Celebrities</a></h6>
									<span>Discover Stars</span>
								</div>
							</div>
						@endif
						<a href="{{ route('celebrities') }}" class="btn">See all celebrities<i class="ion-ios-arrow-right"></i></a>
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
								<iframe class="item-video" src="" data-src="https://www.youtube.com/embed/8ugaeA-nMTc"></iframe>
							</div>
							<div>
								<iframe class="item-video" src="" data-src="https://www.youtube.com/embed/dW1BIid8Osg"></iframe>
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
									<img src="{{ asset('images/uploads/trailer5.jpg') }}" alt="Avengers: Endgame" width="350" height="200">
								</div>
								<div class="trailer-infor">
									<h4 class="desc">Avengers: Endgame</h4>
									<p>2:29</p>
								</div>
							</div>
							<div class="item">
								<div class="trailer-img">
									<img src="{{ asset('images/uploads/trailer6.jpg') }}" alt="Black Widow" width="350" height="200">
								</div>
								<div class="trailer-infor">
									<h4 class="desc">Black Widow: Official Trailer</h4>
									<p>2:32</p>
								</div>
							</div>
							<div class="item">
								<div class="trailer-img">
									<img src="{{ asset('images/uploads/trailer3.png') }}" alt="Spider-Man: No Way Home" width="350" height="200">
								</div>
								<div class="trailer-infor">
									<h4 class="desc">Spider-Man: No Way Home</h4>
									<p>2:45</p>
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
							@if(isset($latestNews) && !empty($latestNews))
								@php 
									// Filter news to only include those with valid images and exclude Reddit
									$newsWithImages = collect($latestNews)->filter(function($item) {
										return isset($item['image']) && 
											   !empty($item['image']) && 
											   $item['image'] !== null &&
											   filter_var($item['image'], FILTER_VALIDATE_URL) &&
											   (!isset($item['source_key']) || $item['source_key'] !== 'reddit');
									});
									$movieNews = $newsWithImages->take(1)->toArray();
								@endphp
								@if(!empty($movieNews))
									@foreach($movieNews as $news)
									<div class="blog-item-style-1">
										<img src="{{ $news['image'] }}" alt="{{ $news['title'] ?? 'Movie News' }}" width="170" height="250" style="object-fit: cover;">
										<div class="blog-it-infor">
											<h3>
												@if(isset($news['url']))
												<a href="{{ $news['url'] }}" target="_blank">{{ $news['title'] ?? 'Latest Movie News' }}</a>
												@else
												<a href="#">{{ $news['title'] ?? 'Latest Movie News' }}</a>
												@endif
											</h3>
											<span class="time">
												{{ isset($news['published_at']) ? \Carbon\Carbon::parse($news['published_at'])->diffForHumans() : (isset($news['pubDate']) ? \Carbon\Carbon::parse($news['pubDate'])->diffForHumans() : '13 hours ago') }}
											</span>
											<p>{{ Str::limit($news['description'] ?? $news['summary'] ?? 'Exclusive entertainment news from the movie industry. Stay updated with the latest releases, celebrity interviews, and behind-the-scenes content from Hollywood and beyond.', 200) }}</p>
										</div>
									</div>
									@endforeach
								@else
									<div class="blog-item-style-1">
										<img src="{{ asset('images/uploads/mv1.jpg') }}" alt="Movie News" width="170" height="250" style="object-fit: cover;">
										<div class="blog-it-infor">
											<h3><a href="#">Latest Movie Industry Updates</a></h3>
											<span class="time">13 hours ago</span>
											<p>Exclusive: <span>Movie Studios</span> continue to bring exciting new releases to theaters. Stay tuned for the latest updates on upcoming blockbusters, indie films, and behind-the-scenes content from your favorite productions.</p>
										</div>
									</div>
								@endif
							@else
							<div class="blog-item-style-1">
								<img src="{{ asset('images/uploads/mv1.jpg') }}" alt="Movie News" width="170" height="250" style="object-fit: cover;">
								<div class="blog-it-infor">
									<h3><a href="#">Latest Movie Industry Updates</a></h3>
									<span class="time">13 hours ago</span>
									<p>Exclusive: <span>Movie Studios</span> continue to bring exciting new releases to theaters. Stay tuned for the latest updates on upcoming blockbusters, indie films, and behind-the-scenes content from your favorite productions.</p>
								</div>
							</div>
							@endif
						</div>
					</div>
				        <div id="tab32" class="tab">
				           <div class="row">
							@if(isset($latestNews) && !empty($latestNews))
								@php 
									// Filter news to only include those with valid images and exclude Reddit
									$newsWithImages = collect($latestNews)->filter(function($item) {
										return isset($item['image']) && 
											   !empty($item['image']) && 
											   $item['image'] !== null &&
											   filter_var($item['image'], FILTER_VALIDATE_URL) &&
											   (!isset($item['source_key']) || $item['source_key'] !== 'reddit');
									});
									$tvNews = $newsWithImages->skip(1)->take(1)->toArray();
								@endphp
								@if(!empty($tvNews))
									@foreach($tvNews as $news)
									<div class="blog-item-style-1">
										<img src="{{ $news['image'] }}" alt="{{ $news['title'] ?? 'TV Shows News' }}" width="170" height="250" style="object-fit: cover;">
										<div class="blog-it-infor">
											<h3>
												@if(isset($news['url']))
												<a href="{{ $news['url'] }}" target="_blank">{{ $news['title'] ?? 'Latest TV Shows News' }}</a>
												@else
												<a href="#">{{ $news['title'] ?? 'Latest TV Shows News' }}</a>
												@endif
											</h3>
											<span class="time">
												{{ isset($news['published_at']) ? \Carbon\Carbon::parse($news['published_at'])->diffForHumans() : (isset($news['pubDate']) ? \Carbon\Carbon::parse($news['pubDate'])->diffForHumans() : '13 hours ago') }}
											</span>
											<p>{{ Str::limit($news['description'] ?? $news['summary'] ?? 'Discover the latest TV shows, streaming series, and television industry news. From Netflix originals to network premieres, stay informed about the evolving world of television entertainment.', 200) }}</p>
										</div>
									</div>
									@endforeach
								@else
									<div class="blog-item-style-1">
										<img src="{{ asset('images/uploads/series-img.jpg') }}" alt="TV Shows News" width="170" height="250" style="object-fit: cover;">
										<div class="blog-it-infor">
											<h3><a href="#">TV Shows & Streaming Updates</a></h3>
											<span class="time">13 hours ago</span>
											<p>Exclusive: <span>Streaming Platforms</span> continue to revolutionize television with new series and innovative content. Stay updated with the latest premieres, renewals, and exclusive behind-the-scenes coverage from your favorite shows.</p>
										</div>
									</div>
								@endif
							@else
							<div class="blog-item-style-1">
								<img src="{{ asset('images/uploads/series-img.jpg') }}" alt="TV Shows News" width="170" height="250" style="object-fit: cover;">
								<div class="blog-it-infor">
									<h3><a href="#">TV Shows & Streaming Updates</a></h3>
									<span class="time">13 hours ago</span>
									<p>Exclusive: <span>Streaming Platforms</span> continue to revolutionize television with new series and innovative content. Stay updated with the latest premieres, renewals, and exclusive behind-the-scenes coverage from your favorite shows.</p>
								</div>
							</div>
							@endif
						</div>
				</div>
				        <div id="tab33" class="tab">
				        	<div class="row">
								@if(isset($latestNews) && !empty($latestNews))
									@php 
										// Filter news to only include those with valid images and exclude Reddit
										$newsWithImages = collect($latestNews)->filter(function($item) {
											return isset($item['image']) && 
												   !empty($item['image']) && 
												   $item['image'] !== null &&
												   filter_var($item['image'], FILTER_VALIDATE_URL) &&
												   (!isset($item['source_key']) || $item['source_key'] !== 'reddit');
										});
										$celebNews = $newsWithImages->skip(2)->take(1)->toArray();
									@endphp
									@if(!empty($celebNews))
										@foreach($celebNews as $news)
										<div class="blog-item-style-1">
											<img src="{{ $news['image'] }}" alt="{{ $news['title'] ?? 'Celebrity News' }}" width="170" height="250" style="object-fit: cover;">
											<div class="blog-it-infor">
												<h3>
													@if(isset($news['url']))
													<a href="{{ $news['url'] }}" target="_blank">{{ $news['title'] ?? 'Latest Celebrity News' }}</a>
													@else
													<a href="#">{{ $news['title'] ?? 'Latest Celebrity News' }}</a>
													@endif
												</h3>
												<span class="time">
													{{ isset($news['published_at']) ? \Carbon\Carbon::parse($news['published_at'])->diffForHumans() : (isset($news['pubDate']) ? \Carbon\Carbon::parse($news['pubDate'])->diffForHumans() : '13 hours ago') }}
												</span>
												<p>{{ Str::limit($news['description'] ?? $news['summary'] ?? 'Follow the latest celebrity news, red carpet events, and exclusive interviews with Hollywood stars. Get insider access to entertainment industry personalities and their upcoming projects.', 200) }}</p>
											</div>
										</div>
										@endforeach
									@else
										<div class="blog-item-style-1">
											<img src="{{ asset('images/uploads/ceb2.jpg') }}" alt="Celebrity News" width="170" height="250" style="object-fit: cover;">
											<div class="blog-it-infor">
												<h3><a href="#">Celebrity Spotlight</a></h3>
												<span class="time">13 hours ago</span>
												<p>Exclusive: <span>Hollywood Stars</span> continue to captivate audiences with their latest projects and red carpet appearances. Follow the latest celebrity interviews, behind-the-scenes moments, and entertainment industry insights.</p>
											</div>
										</div>
									@endif
								@else
								<div class="blog-item-style-1">
									<img src="{{ asset('images/uploads/ceb2.jpg') }}" alt="Celebrity News" width="170" height="250" style="object-fit: cover;">
									<div class="blog-it-infor">
										<h3><a href="#">Celebrity Spotlight</a></h3>
										<span class="time">13 hours ago</span>
										<p>Exclusive: <span>Hollywood Stars</span> continue to captivate audiences with their latest projects and red carpet appearances. Follow the latest celebrity interviews, behind-the-scenes moments, and entertainment industry insights.</p>
									</div>
								</div>
								@endif
				            </div>
			       	 	</div>
				    </div>
				</div>
				<div class="morenew">
					<div class="title-hd">
						<h3>More news on Blockbuster</h3>
						<a href="{{ route('blog') }}" class="viewall">See all Movies news<i class="ion-ios-arrow-right"></i></a>
					</div>
					<div class="more-items">
						<div class="left">
							@if(isset($latestNews) && count($latestNews) > 3)
								@foreach(array_slice($latestNews, 3, 2) as $news)
								<div class="more-it">
									<h6>
										@if(isset($news['url']) && $news['url'])
										<a href="{{ $news['url'] }}" target="_blank">{{ Str::limit($news['title'] ?? 'Entertainment News Update', 75) }}</a>
										@else
										<a href="{{ route('blog') }}">{{ Str::limit($news['title'] ?? 'Entertainment News Update', 75) }}</a>
										@endif
									</h6>
									<span class="time">{{ isset($news['published_at']) ? \Carbon\Carbon::parse($news['published_at'])->diffForHumans() : (isset($news['pubDate']) ? \Carbon\Carbon::parse($news['pubDate'])->diffForHumans() : '13 hours ago') }}</span>
								</div>
								@endforeach
							@else
							<div class="more-it">
								<h6><a href="{{ route('blog') }}">Latest Entertainment Industry Updates</a></h6>
								<span class="time">13 hours ago</span>
							</div>
							<div class="more-it">
								<h6><a href="{{ route('blog') }}">Behind-the-Scenes Cinema News</a></h6>
								<span class="time">Recently</span>
							</div>
							@endif
						</div>
						<div class="right">
							@if(isset($latestNews) && count($latestNews) > 5)
								@foreach(array_slice($latestNews, 5, 2) as $news)
								<div class="more-it">
									<h6>
										@if(isset($news['url']) && $news['url'])
										<a href="{{ $news['url'] }}" target="_blank">{{ Str::limit($news['title'] ?? 'Movie Industry News', 75) }}</a>
										@else
										<a href="{{ route('blog') }}">{{ Str::limit($news['title'] ?? 'Movie Industry News', 75) }}</a>
										@endif
									</h6>
									<span class="time">{{ isset($news['published_at']) ? \Carbon\Carbon::parse($news['published_at'])->diffForHumans() : (isset($news['pubDate']) ? \Carbon\Carbon::parse($news['pubDate'])->diffForHumans() : '13 hours ago') }}</span>
								</div>
								@endforeach
							@else
							<div class="more-it">
								<h6><a href="{{ route('blog') }}">Hollywood Production News</a></h6>
								<span class="time">13 hours ago</span>
							</div>
							<div class="more-it">
								<h6><a href="{{ route('blog') }}">Celebrity Project Announcements</a></h6>
								<span class="time">Recently</span>
							</div>
							@endif
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
					
					<!-- Famous Movie Quote -->
					<div style="margin: 40px 0; padding: 20px; text-align: center;">
						@php
							$movieQuotes = [
								'Frankly, my dear, I don\'t give a damn. — Gone with the Wind (1939)',
								'I\'m gonna make him an offer he can\'t refuse. — The Godfather (1972)',
								'You don\'t understand! I coulda had class. I coulda been a contender… — On the Waterfront (1954)',
								'Here\'s looking at you, kid. — Casablanca (1942)',
								'Go ahead, make my day. — Sudden Impact (1983)',
								'May the Force be with you. — Star Wars (1977)',
								'You talking to me? — Taxi Driver (1976)',
								'E.T. phone home. — E.T. the Extra-Terrestrial (1982)',
								'There\'s no place like home. — The Wizard of Oz (1939)',
								'I\'ll be back. — The Terminator (1984)',
								'You\'re gonna need a bigger boat. — Jaws (1975)',
								'Here\'s Johnny! — The Shining (1980)',
								'I\'m the king of the world! — Titanic (1997)',
								'Show me the money! — Jerry Maguire (1996)',
								'I\'ll have what she\'s having. — When Harry Met Sally... (1989)',
								'You can\'t handle the truth! — A Few Good Men (1992)',
								'Life is like a box of chocolates. You never know what you\'re gonna get. — Forrest Gump (1994)',
								'I see dead people. — The Sixth Sense (1999)',
								'Why so serious? — The Dark Knight (2008)',
								'To infinity and beyond! — Toy Story (1995)',
								'Bond. James Bond. — Dr. No (1962)',
								'Keep your friends close, but your enemies closer. — The Godfather Part II (1974)',
								'Say \'hello\' to my little friend! — Scarface (1983)',
								'Hasta la vista, baby. — Terminator 2: Judgment Day (1991)',
								'I\'m walkin\' here! — Midnight Cowboy (1969)',
								'They may take our lives, but they\'ll never take our freedom! — Braveheart (1995)',
								'You had me at \'hello\'. — Jerry Maguire (1996)',
								'Rosebud. — Citizen Kane (1941)',
								'The stuff that dreams are made of. — The Maltese Falcon (1941)',
								'After all, tomorrow is another day! — Gone with the Wind (1939)',
								'Keep the change, ya filthy animal. — Home Alone (1990)',
								'I\'m as mad as hell, and I\'m not going to take this anymore! — Network (1976)',
								'Of all the gin joints in all the towns in all the world, she walks into mine. — Casablanca (1942)',
								'They\'re here! — Poltergeist (1982)',
								'Nobody puts Baby in a corner. — Dirty Dancing (1987)',
								'I feel the need — the need for speed! — Top Gun (1986)',
								'Carpe diem. Seize the day, boys. — Dead Poets Society (1989)',
								'I\'m just a girl, standing in front of a boy, asking him to love her. — Notting Hill (1999)',
								'As if! — Clueless (1995)',
								'Shaken, not stirred. — Goldfinger (1964)',
								'My precious. — The Lord of the Rings: The Two Towers (2002)',
								'You shall not pass! — The Lord of the Rings: The Fellowship of the Ring (2001)',
								'The first rule of Fight Club is: you do not talk about Fight Club. — Fight Club (1999)',
								'Life, uh, finds a way. — Jurassic Park (1993)',
								'I am Spartacus! — Spartacus (1960)',
								'The horror! The horror! — Apocalypse Now (1979)',
								'Wax on, wax off. — The Karate Kid (1984)',
								'You complete me. — Jerry Maguire (1996)',
								'I\'m not bad. I\'m just drawn that way. — Who Framed Roger Rabbit (1988)',
								'Roads? Where we\'re going, we don\'t need roads. — Back to the Future (1985)'
							];
							$randomQuote = collect($movieQuotes)->random(1)->first();
						@endphp
						
						<p style="font-size: 20px; font-weight: bold; color: #ffffff; margin: 30px 0; line-height: 5.0; padding: 15px;">
							{!! nl2br(e($randomQuote)) !!}
						</p>
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
