@extends('layouts.app')

@section('title', ($movie['title'] ?? 'Movie Details') . ' - Cinema Paradiso')

@push('styles')
<style>
/* Movie Single Page Styles */
.hero.mv-single-hero {
    background: linear-gradient(135deg, #020d18 0%, #0d1b2a 100%);
    padding: 40px 0;
}

.page-single.movie-single {
    background: #020d18;
    min-height: 100vh;
    padding: 50px 0;
    position: relative;
}

.page-single.movie-single::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url('{{ isset($images["backdrops"][0]["file_path"]) ? app("App\Services\MovieService")->getImageUrl($images["backdrops"][0]["file_path"], "original") : "" }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    opacity: 0.25;
    z-index: 0;
}

.page-single.movie-single .container {
    position: relative;
    z-index: 1;
}

.movie-single-ct {
    color: #fff;
}

.movie-single-ct h1.bd-hd {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 20px;
    color: #fff;
}

.movie-single-ct h1.bd-hd span {
    color: #dcf836;
    font-weight: 300;
    margin-left: 15px;
}

.movie-img {
    text-align: center;
    margin-bottom: 30px;
}

.movie-img img {
    width: 100%;
    max-width: 350px;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
}

.movie-btn {
    text-align: center;
    margin-top: 20px;
}

.btn-transform {
    display: inline-block;
    margin: 0 10px;
}

.redbtn, .yellowbtn {
    display: inline-block;
    padding: 15px 30px;
    text-decoration: none;
    font-weight: bold;
    transition: all 0.3s ease;
    margin: 5px;
}

.redbtn {
    background: #955567ff;
    color: #fff;
}

.redbtn:hover {
    background: #b8002e;
    color: #fff;
    text-decoration: none;
}

.yellowbtn {
    background: #dcf836;
    color: #020d18;
}

.yellowbtn:hover {
    background: #c9e632;
    color: #020d18;
    text-decoration: none;
}

.social-btn {
    margin: 20px 0;
}

.social-btn .parent-btn {
    display: inline-block;
    padding: 10px 20px;
    background: #3e9fd8;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    margin-right: 10px;
    transition: all 0.3s ease;
}

.social-btn .parent-btn:hover {
    background: #ec6eab;
    color: #fff;
    text-decoration: none;
}

.movie-rate {
    display: flex;
    align-items: center;
    margin: 20px 0;
    gap: 30px;
}

.movie-rate .rate {
    display: flex;
    align-items: center;
    gap: 10px;
}

.movie-rate .rate i {
    color: #dcf836;
    font-size: 24px;
}

.movie-rate .rate p {
    margin: 0;
    color: #fff;
    font-size: 18px;
}

.movie-rate .rate p span {
    font-weight: bold;
    color: #dcf836;
}

.rate-star {
    display: flex;
    align-items: center;
    gap: 5px;
}

.rate-star p {
    margin: 0;
    color: #fff;
    margin-right: 10px;
}

.rate-star i {
    color: #dcf836;
    font-size: 18px;
    cursor: pointer;
    transition: color 0.3s ease;
}

.rate-star i:hover {
    color: #fff;
}

.movie-tabs {
    margin-top: 40px;
}

.tabs .tab-links {
    list-style: none;
    padding: 0;
    margin: 0 0 30px 0;
    border-bottom: none;
    display: flex;
    gap: 0;
}

.tabs .tab-links li {
    background: rgba(35, 58, 80, 0.5);
}

.tabs .tab-links li.active {
    background: #dcf836;
}

.tabs .tab-links li a {
    display: block;
    padding: 10px 15px;
    color: #fff;
    text-decoration: none;
    font-weight: bold;
    font-size: 12px;
    transition: all 0.3s ease;
}

.tabs .tab-links li.active a {
    color: #020d18;
}

.tabs .tab-links li a:hover {
    color: #dcf836;
    text-decoration: none;
}

.tabs .tab-links li.active a:hover {
    color: #020d18;
}

.tab-content .tab {
    display: none;
}

.tab-content .tab.active {
    display: block;
}

.sb-it {
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: none;
}

.sb-it h6 {
    color: #dcf836;
    font-weight: bold;
    margin-bottom: 5px;
    font-size: 14px;
    text-transform: uppercase;
}

.sb-it p {
    margin: 0;
    color: #fff;
}

.sb-it a {
    color: #3e9fd8;
    text-decoration: none;
}

.sb-it a:hover {
    color: #ec6eab;
    text-decoration: none;
}

.tags .time {
    display: inline-block;
    background: #233a50;
    padding: 5px 12px;
    margin: 3px;
    border-radius: 15px;
    font-size: 12px;
}

.tags .time a {
    color: #fff;
    text-decoration: none;
}

.tags .time:hover {
    background: #3e9fd8;
}

.title-hd-sm {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 30px 0 20px 0;
    padding-bottom: 10px;
    border-bottom: none;
}

.title-hd-sm h4 {
    color: #fff;
    font-weight: bold;
    margin: 0;
    text-transform: uppercase;
}

.title-hd-sm .time {
    color: #3e9fd8;
    text-decoration: none;
    font-size: 14px;
}

.title-hd-sm .time:hover {
    color: #ec6eab;
}

.mvcast-item {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 20px;
    line-height: 1.6;
}

.cast-it {
    display: inline-block;
    background: none;
    padding: 0;
    border: none;
    margin: 0;
}

.cast-left {
    margin: 0;
}

.cast-left a {
    color: #3e9fd8;
    text-decoration: none;
    font-weight: normal;
    font-size: 14px;
    display: inline;
    margin: 0;
    padding: 0;
}

.cast-left a:hover {
    color: #ec6eab;
}

.cast-it p {
    margin: 0;
    color: #ccc;
    font-size: 14px;
}

.mvsingle-item {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-bottom: 30px;
}

.mvsingle-item img {
    width: 100%;
    border-radius: 8px;
    transition: transform 0.3s ease;
}

.mvsingle-item img:hover {
    transform: scale(1.05);
}

.ov-item {
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 10px;
}

.vd-it {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
}

.vd-img {
    width: 100%;
    height: auto;
}

.fancybox-media {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.sticky-sb {
    position: sticky;
    top: 20px;
}

.ads img {
    width: 100%;
    border-radius: 8px;
    margin-top: 20px;
}

/* Related Movies Styling */
.movie-item-style-2 {
    display: flex;
    background: rgba(35, 58, 80, 0.3);
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
    gap: 15px;
}

.movie-item-style-2 img {
    width: 80px;
    height: 120px;
    object-fit: cover;
    border-radius: 6px;
    flex-shrink: 0;
}

.mv-item-infor {
    flex: 1;
    overflow: hidden;
}

.mv-item-infor h6 {
    margin: 0 0 8px 0;
    font-size: 14px;
    line-height: 1.3;
}

.mv-item-infor h6 a {
    color: #fff;
    text-decoration: none;
    font-weight: bold;
}

.mv-item-infor h6 a:hover {
    color: #dcf836;
}

.mv-item-infor h6 span {
    color: #ccc;
    font-weight: normal;
}

.mv-item-infor p {
    margin: 5px 0;
    font-size: 12px;
    line-height: 1.4;
    color: #ccc;
}

.mv-item-infor p.rate {
    color: #dcf836;
    font-weight: bold;
}

.mv-item-infor p.rate i {
    color: #dcf836;
    margin-right: 3px;
}

.mv-item-infor p.describe {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

.mv-item-infor p a {
    color: #3e9fd8;
    text-decoration: none;
}

.mv-item-infor p a:hover {
    color: #ec6eab;
}
@media (max-width: 768px) {
    .movie-single-ct h1.bd-hd {
        font-size: 1.8rem;
    }
    
    .movie-rate {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .tabs .tab-links {
        flex-wrap: wrap;
    }
    
    .tabs .tab-links li a {
        padding: 8px 10px;
        font-size: 11px;
    }
    
    .mvcast-item {
        gap: 10px;
    }
    
    .cast-left a {
        font-size: 12px;
    }
    
    .cast-it {
        display: inline-block;
    }
    
    .movie-item-style-2 {
        flex-direction: column;
        text-align: center;
    }
    
    .movie-item-style-2 img {
        width: 60px;
        height: 90px;
        margin: 0 auto 10px;
    }
    
    .mv-item-infor h6 {
        font-size: 13px;
    }
    
    .mv-item-infor p {
        font-size: 11px;
    }
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

<div class="hero mv-single-hero">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<!-- Simple breadcrumb without hero background -->
			</div>
		</div>
	</div>
</div>

<div class="page-single movie-single movie_single">
	<div class="container">
		<div class="row ipad-width2">
			<div class="col-md-4 col-sm-12 col-xs-12">
				<div class="movie-img sticky-sb">
					<img src="{{ app('App\Services\MovieService')->getImageUrl($movie['poster_path'] ?? null, 'w500') }}" 
					     alt="{{ $movie['title'] ?? 'Movie Poster' }}">
					<div class="movie-btn">	
						<div class="btn-transform transform-vertical red">
							@if($videos && isset($videos['results']) && count($videos['results']) > 0)
								@php
									$trailer = collect($videos['results'])->firstWhere('type', 'Trailer');
									if (!$trailer) $trailer = $videos['results'][0];
									$trailerUrl = $trailer ? "https://www.youtube.com/embed/{$trailer['key']}" : '#';
								@endphp
								<div><a href="#" class="item item-1 redbtn"> <i class="ion-play"></i> Watch Trailer</a></div>
								<div><a href="{{ $trailerUrl }}" class="item item-2 redbtn fancybox-media hvr-grow"><i class="ion-play"></i></a></div>
							@else
								<div><a href="#" class="item item-1 redbtn"> <i class="ion-play"></i> Trailer Unavailable</a></div>
							@endif
						</div>
						<div class="btn-transform transform-vertical">
							@if(isset($movie['homepage']) && $movie['homepage'])
								<div><a href="{{ $movie['homepage'] }}" class="item item-1 yellowbtn" target="_blank"> <i class="ion-card"></i> Official Site</a></div>
								<div><a href="{{ $movie['homepage'] }}" class="item item-2 yellowbtn" target="_blank"><i class="ion-card"></i></a></div>
							@else
								<div><a href="#" class="item item-1 yellowbtn"> <i class="ion-card"></i> Buy ticket</a></div>
								<div><a href="#" class="item item-2 yellowbtn"><i class="ion-card"></i></a></div>
							@endif
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-8 col-sm-12 col-xs-12">
				<div class="movie-single-ct main-content">
					<h1 class="bd-hd">{{ $movie['title'] ?? 'Untitled' }} 
						@if(isset($movie['release_date']))
							<span>{{ date('Y', strtotime($movie['release_date'])) }}</span>
						@endif
					</h1>
					<div class="social-btn">
						<a href="#" class="parent-btn"><i class="ion-heart"></i> Add to Favorite</a>
						<div class="hover-bnt">
							<a href="#" class="parent-btn"><i class="ion-android-share-alt"></i>share</a>
							<div class="hvr-item">
								<a href="#" class="hvr-grow"><i class="ion-social-facebook"></i></a>
								<a href="#" class="hvr-grow"><i class="ion-social-twitter"></i></a>
								<a href="#" class="hvr-grow"><i class="ion-social-googleplus"></i></a>
								<a href="#" class="hvr-grow"><i class="ion-social-youtube"></i></a>
							</div>
						</div>		
					</div>
					<div class="movie-rate">
						<div class="rate">
							<i class="ion-android-star"></i>
							<p><span>{{ number_format($movie['vote_average'] ?? 0, 1) }}</span> /10<br>
								<span class="rv">{{ number_format($movie['vote_count'] ?? 0) }} Reviews</span>
							</p>
						</div>
						<div class="rate-star">
							<p>Rate This Movie:  </p>
							@for($i = 1; $i <= 10; $i++)
								@if($i <= ($movie['vote_average'] ?? 0))
									<i class="ion-ios-star"></i>
								@else
									<i class="ion-ios-star-outline"></i>
								@endif
							@endfor
						</div>
					</div>
					<div class="movie-tabs">
						<div class="tabs">
							<ul class="tab-links tabs-mv">
								<li class="active"><a href="#overview">Overview</a></li>
								<li><a href="#reviews"> Reviews</a></li>
								<li><a href="#cast">  Cast & Crew </a></li>
								<li><a href="#media"> Media</a></li> 
								<li><a href="#moviesrelated"> Related Movies</a></li>                        
							</ul>
						    <div class="tab-content">
						        <div id="overview" class="tab active">
						            <div class="row">
						            	<div class="col-md-8 col-sm-12 col-xs-12">
						            		<p>{{ $movie['overview'] ?? 'No overview available for this movie.' }}</p>
						            		
						            		@if(isset($credits['cast']) && count($credits['cast']) > 0)
											<div class="title-hd-sm">
												<h4>Cast</h4>
											</div>
											<!-- movie cast -->
											<div class="mvcast-item">											
												@foreach(array_slice($credits['cast'] ?? [], 0, 12) as $actor)
												<div class="cast-it">
													<div class="cast-left">
														<a href="{{ route('celebrities.show', $actor['id']) }}">{{ $actor['name'] }}</a>
													</div>
												</div>
												@endforeach
											</div>
											@endif
						            	</div>
						            	<div class="col-md-4 col-xs-12 col-sm-12">
						            		@if(isset($credits['crew']))
						            			@php 
						            				$director = collect($credits['crew'])->firstWhere('job', 'Director');
						            				$writers = collect($credits['crew'])->where('department', 'Writing');
						            			@endphp
						            		@endif
						            		
						            		@if($director)
						            		<div class="sb-it">
						            			<h6>Director: </h6>
						            			<p><a href="{{ route('celebrities.show', $director['id']) }}">{{ $director['name'] }}</a></p>
						            		</div>
						            		@endif
						            		
						            		@if($writers->isNotEmpty())
						            		<div class="sb-it">
						            			<h6>Writer: </h6>
						            			<p>
						            				@foreach($writers->take(3) as $index => $writer)
						            					<a href="{{ route('celebrities.show', $writer['id']) }}">{{ $writer['name'] }}</a>@if(!$loop->last), @endif
						            				@endforeach
						            			</p>
						            		</div>
						            		@endif
						            		
						            		@if(isset($credits['cast']) && count($credits['cast']) > 0)
						            		<div class="sb-it">
						            			<h6>Stars: </h6>
						            			<p>
						            				@foreach(array_slice($credits['cast'], 0, 4) as $index => $actor)
						            					<a href="{{ route('celebrities.show', $actor['id']) }}">{{ $actor['name'] }}</a>@if($index < 3 && isset($credits['cast'][$index + 1])), @endif
						            				@endforeach
						            			</p>
						            		</div>
						            		@endif
						            		
						            		@if(isset($movie['genres']) && count($movie['genres']) > 0)
						            		<div class="sb-it">
						            			<h6>Genres:</h6>
						            			<p>
						            				@foreach($movie['genres'] as $index => $genre)
						            					<a href="{{ route('movies.genre', $genre['id']) }}">{{ $genre['name'] }}</a>@if(!$loop->last), @endif
						            				@endforeach
						            			</p>
						            		</div>
						            		@endif
						            		
						            		@if(isset($movie['release_date']))
						            		<div class="sb-it">
						            			<h6>Release Date:</h6>
						            			<p>{{ date('F j, Y', strtotime($movie['release_date'])) }}</p>
						            		</div>
						            		@endif
						            		
						            		@if(isset($movie['runtime']) && $movie['runtime'])
						            		<div class="sb-it">
						            			<h6>Run Time:</h6>
						            			<p>{{ $movie['runtime'] }} min</p>
						            		</div>
						            		@endif
						            		
						            		@if(isset($movie['vote_average']))
						            		<div class="sb-it">
						            			<h6>TMDB Rating:</h6>
						            			<p>{{ number_format($movie['vote_average'], 1) }}/10</p>
						            		</div>
						            		@endif
						            		
						            		@if(isset($movie['keywords']) && count($movie['keywords']) > 0)
						            		<div class="sb-it">
						            			<h6>Plot Keywords:</h6>
						            			<p class="tags">
						            				@foreach(array_slice($movie['keywords'], 0, 5) as $keyword)
						            					<span class="time"><a href="#">{{ $keyword['name'] }}</a></span>
													@endforeach
						            			</p>
						            		</div>
						            		@endif
						            		
						            		<div class="ads">
												<img src="{{ asset('images/uploads/ads1.png') }}" alt="">
											</div>
						            	</div>
						            </div>
						        </div>
						        <div id="reviews" class="tab">
						           <div class="row">
						            	<div class="rv-hd">
						            		<div class="div">
							            		<h3>Related Movies To</h3>
						       	 				<h2>{{ $movie['title'] ?? 'This Movie' }}</h2>
							            	</div>
							            	<a href="#" class="redbtn">Write Review</a>
						            	</div>
						            	<div class="topbar-filter">
											<p>Found <span>{{ $movie['vote_count'] ?? 0 }} reviews</span> in total</p>
											<label>Filter by:</label>
											<select>
												<option value="popularity">Popularity Descending</option>
												<option value="popularity">Popularity Ascending</option>
												<option value="rating">Rating Descending</option>
												<option value="rating">Rating Ascending</option>
												<option value="date">Release date Descending</option>
												<option value="date">Release date Ascending</option>
											</select>
										</div>
										<div class="mv-user-review-item">
											<div class="user-infor">
												<img src="{{ asset('images/uploads/userava1.jpg') }}" alt="">
												<div>
													<h3>Great movie experience!</h3>
													<div class="no-star">
														@for($i = 1; $i <= 10; $i++)
															@if($i <= 8)
																<i class="ion-android-star"></i>
															@else
																<i class="ion-android-star last"></i>
															@endif
														@endfor
													</div>
													<p class="time">
														{{ date('j F Y') }} by <a href="#"> moviefan</a>
													</p>
												</div>
											</div>
											<p>{{ $movie['overview'] ?? 'This movie provides an excellent cinematic experience with great storytelling and performances.' }}</p>
										</div>
						            </div>
						        </div>
						        <div id="cast" class="tab">
						        	<div class="row">
						            	<h3>Cast & Crew of</h3>
					       	 			<h2>{{ $movie['title'] ?? 'This Movie' }}</h2>
										
										@if(isset($credits['crew']))
										<div class="title-hd-sm">
											<h4>Directors & Writers</h4>
										</div>
										<div class="mvcast-item">
											@foreach(collect($credits['crew'])->whereIn('job', ['Director', 'Writer', 'Screenplay'])->take(10) as $crew)											
											<div class="cast-it">
												<div class="cast-left">
													<a href="{{ route('celebrities.show', $crew['id']) }}">{{ $crew['name'] }}</a>
												</div>
											</div>
											@endforeach
										</div>
										@endif
										
										@if(isset($credits['cast']) && count($credits['cast']) > 0)
										<div class="title-hd-sm">
											<h4>Cast</h4>
										</div>
										<div class="mvcast-item">											
											@foreach(array_slice($credits['cast'], 0, 20) as $actor)
											<div class="cast-it">
												<div class="cast-left">
													<a href="{{ route('celebrities.show', $actor['id']) }}">{{ $actor['name'] }}</a>
												</div>
											</div>
											@endforeach
										</div>
										@endif
						            </div>
					       	 	</div>
					       	 	<div id="media" class="tab">
						        	<div class="row">
						        		<div class="rv-hd">
						            		<div>
						            			<h3>Videos & Photos of</h3>
					       	 					<h2>{{ $movie['title'] ?? 'This Movie' }}</h2>
						            		</div>
						            	</div>
						            	
						            	@if(isset($videos['results']) && count($videos['results']) > 0)
						            	<div class="title-hd-sm">
											<h4>Videos <span>({{ count($videos['results']) }})</span></h4>
										</div>
										<div class="mvsingle-item media-item">
											@foreach($videos['results'] as $video)
											<div class="vd-item">
												<div class="vd-it">
													<img class="vd-img" src="https://img.youtube.com/vi/{{ $video['key'] }}/mqdefault.jpg" alt="">
													<a class="fancybox-media hvr-grow" href="https://www.youtube.com/embed/{{ $video['key'] }}"><img src="{{ asset('images/uploads/play-vd.png') }}" alt=""></a>
												</div>
												<div class="vd-infor">
													<h6><a href="#">{{ $video['name'] }}</a></h6>
													<p class="time">{{ $video['type'] }}</p>
												</div>
											</div>
											@endforeach
										</div>
										@endif
										
										@if(isset($images['backdrops']) && count($images['backdrops']) > 0)
										<div class="title-hd-sm">
											<h4>Photos <span> ({{ count($images['backdrops']) }})</span></h4>
										</div>
										<div class="mvsingle-item">
											@php
												$uniqueBackdrops = collect($images['backdrops'])->unique('file_path');
											@endphp
											@foreach($uniqueBackdrops as $image)
											<a class="img-lightbox" data-fancybox-group="gallery" href="{{ app('App\Services\MovieService')->getImageUrl($image['file_path'], 'original') }}">
												<img src="{{ app('App\Services\MovieService')->getImageUrl($image['file_path'], 'w300') }}" alt="">
											</a>
											@endforeach
										</div>
										@endif
						        	</div>
					       	 	</div>
					       	 	<div id="moviesrelated" class="tab">
					       	 		<div class="row">
					       	 			<h3>Related Movies To</h3>
					       	 			<h2>{{ $movie['title'] ?? 'This Movie' }}</h2>
					       	 			<div class="topbar-filter">
											<p>Found <span>12 movies</span> in total</p>
											<label>Sort by:</label>
											<select>
												<option value="popularity">Popularity Descending</option>
												<option value="popularity">Popularity Ascending</option>
												<option value="rating">Rating Descending</option>
												<option value="rating">Rating Ascending</option>
												<option value="date">Release date Descending</option>
												<option value="date">Release date Ascending</option>
											</select>
										</div>
										
										@if(isset($movie['genres']) && count($movie['genres']) > 0)
											@php
												$genreId = $movie['genres'][0]['id'];
												$relatedMovies = app('App\Services\MovieService')->discoverMoviesByGenre($genreId, 1);
											@endphp
											
											@if($relatedMovies && isset($relatedMovies['results']))
												@foreach(array_slice($relatedMovies['results'], 0, 5) as $related)
												<div class="movie-item-style-2">
													<img src="{{ app('App\Services\MovieService')->getImageUrl($related['poster_path'], 'w300') }}" alt="">
													<div class="mv-item-infor">
														<h6><a href="{{ route('movies.show', $related['id']) }}">{{ $related['title'] }} <span>({{ date('Y', strtotime($related['release_date'] ?? '')) }})</span></a></h6>
														<p class="rate"><i class="ion-android-star"></i><span>{{ number_format($related['vote_average'], 1) }}</span> /10</p>
														<p class="describe">{{ Str::limit($related['overview'], 200) }}</p>
														<p class="run-time">Release: {{ date('j M Y', strtotime($related['release_date'] ?? '')) }}</p>
														@if(isset($related['genres']))
															<p>Genres: 
																@foreach(array_slice($related['genres'], 0, 3) as $genre)
																	<a href="{{ route('movies.genre', $genre['id']) }}">{{ $genre['name'] }}</a>@if(!$loop->last), @endif
																@endforeach
															</p>
														@endif
													</div>
												</div>
												@endforeach
											@endif
										@endif
					       	 		</div>
					       	 	</div>
						    </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Tab functionality
    $('.tabs .tab-links a').on('click', function(e) {
        e.preventDefault();
        var currentAttrValue = $(this).attr('href');
        
        $('.tab-content ' + currentAttrValue).show().siblings().hide();
        $(this).parent('li').addClass('active').siblings().removeClass('active');
    });

    // Initialize fancybox for images and videos
    if (typeof $.fancybox !== 'undefined') {
        $('.fancybox-media').fancybox({
            openEffect: 'none',
            closeEffect: 'none',
            helpers: {
                media: {}
            }
        });
        
        $('.img-lightbox').fancybox({
            openEffect: 'elastic',
            closeEffect: 'elastic'
        });
    }
    
    // Star rating interaction
    $('.rate-star i').on('click', function() {
        var rating = $(this).index() + 1;
        $(this).prevAll().addBack().removeClass('ion-ios-star-outline').addClass('ion-ios-star');
        $(this).nextAll().removeClass('ion-ios-star').addClass('ion-ios-star-outline');
    });
});

// Logout function
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