@extends('layouts.app')

@section('title', ($movie['title'] ?? 'Movie Details') . ' - Cinema Paradiso')

@push('styles')
<style>
/* Ensure body and html have dark background */
html, body {
    background-color: #020d18 !important;
    min-height: 100%;
}

/* Movie Single Page Styles */
.hero.mv-single-hero {
    background: linear-gradient(135deg, #020d18 0%, #0d1b2a 100%);
    padding: 40px 0;
}

.page-single.movie-single {
    background: #020d18 !important;
    min-height: 100vh;
    padding: 50px 0 100px;
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
    opacity: 0.10;
    z-index: 0;
}

.page-single.movie-single .container {
    position: relative;
    z-index: 1;
    background: transparent;
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
    color: #e9d736;
    font-weight: 300;
    margin-left: 15px;
}

.movie-img {
    text-align: center;
    margin-bottom: 30px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
}

.movie-img img {
    width: 100% !important;
    max-width: 400px !important;
    min-height: 550px !important;
    max-height: 650px !important;
    height: auto !important;
    object-fit: cover !important;
    object-position: center top !important;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
}

@media (max-width: 991px) {
    .movie-img img {
        max-width: 350px !important;
        min-height: 500px !important;
        max-height: 600px !important;
    }
}

@media (max-width: 767px) {
    .movie-img img {
        max-width: 300px !important;
        min-height: 450px !important;
        max-height: 550px !important;
    }
}

.movie-btn {
    text-align: center !important;
    margin-top: 20px !important;
    /* Add any other styles you want to ensure override */
    padding: 0 !important;
    background-color: transparent !important;
    border: none !important;
}

.btn-transform {
    display: inline-block;
    margin: 0 !important;
    margin-bottom: 10px !important;
}

.redbtn, .yellowbtn {
    display: inline-block;
    padding: 0;
    margin: 0;
    margin-bottom: 10px;
    text-decoration: none;
    font-weight: bold;
    border: none;
	border-radius: 5px;
    background: none;
    text-align: center !important;
    width: 100%;
}

.redbtn {
    background: #ec6eab !important;
    color: #fff !important;
}

.yellowbtn {
    background: #e9d736 !important;
    color: #020d18 !important;
}

/* Movie Action Buttons */
.movie-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin: 25px 0;
    padding: 0;
}

.action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: transparent;
    border: none;
    color: #fff;
    text-decoration: none;
    padding: 10px 15px;
    transition: all 0.3s ease;
    cursor: pointer;
    min-width: 80px;
}

.action-btn:hover {
    color: #e9d736;
    text-decoration: none;
    transform: translateY(-2px);
}

.action-btn i {
    font-size: 24px;
    margin-bottom: 5px;
    color: #fff;
    transition: color 0.3s ease;
}

.action-btn:hover i {
    color: #e9d736;
}

.action-btn span {
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.rating-display {
    display: flex;
    align-items: center;
    gap: 8px;
}

.rating-display .stars {
    color: #e9d736;
    font-size: 16px;
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
    color: #e9d736;
    font-size: 24px;
}

.movie-rate .rate p {
    margin: 0;
    color: #fff;
    font-size: 18px;
}

.movie-rate .rate p span {
    font-weight: bold;
    color: #e9d736;
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
    color: #e9d736;
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
    background: transparent;
}

.tabs .tab-links li.active {
    background: transparent;
}

.tabs .tab-links li a {
    display: block;
    padding: 0px -10px;
    color: #fff;
    text-decoration: none;
    font-weight: bold;
    font-size: 12px;
    transition: all 0.3s ease;
}

.tabs .tab-links li.active a {
    color: #e9d736;
}

.tabs .tab-links li a:hover {
    color: #e9d736;
    text-decoration: none;
    background: transparent;
}

.tabs .tab-links li.active a:hover {
    color: #e9d736;
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
    color: #e9d736;
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

/* Name Badge Styles for Overview Section */
.cast-names {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 8px;
}

.name-badge {
    display: inline-block;
    border: 1px solid #405266;
    border-radius: 4px;
    padding-left:2px;
    padding-right:2px;
    background: #0f2133;
    transition: all 0.3s ease;
    margin-bottom: 8px;
}

.name-badge:hover {
    border-color: #dcf836;
    background: rgba(220, 248, 54, 0.1);
    transform: translateY(-1px);
}

.name-badge a {
    color: #abb7c4;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
}

.name-badge:hover a {
    color: #dcf836;
}

/* Overview Details Layout */
#overview .sb-it {
    margin-bottom: 25px;
}

#overview .sb-it h6 {
    font-size: 13px;
    margin-bottom: 10px;
    letter-spacing: 0.5px;
}

#overview .sb-it p {
    line-height: 1.6;
    font-size: 14px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    #overview .col-md-4 {
        margin-bottom: 20px;
    }
    
    .name-badge {
        font-size: 12px;
        padding: 5px 10px;
    }
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

/* Media section - Videos */
.media-item {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 8px;
    margin-bottom: 20px;
}

.media-item .vd-item {
    width: 100%;
    margin-bottom: 0;
}

.media-item .vd-it {
    position: relative;
    width: 100%;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
    overflow: hidden;
    border-radius: 6px;
}

.media-item .vd-it img.vd-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.media-item .vd-it a {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 2;
}

.media-item .vd-infor {
    margin-top: 8px;
}

.media-item .vd-infor h6 {
    margin-bottom: 3px;
    font-size: 13px;
}

.media-item .vd-infor .time {
    font-size: 11px;
}

/* Media section - Photos */
#media .mvsingle-item {
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 8px;
}

#media .mvsingle-item img {
    height: 100px;
    object-fit: cover;
}

@media (max-width: 768px) {
    .media-item {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 6px;
    }
    
    #media .mvsingle-item {
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 6px;
    }
    
    #media .mvsingle-item img {
        height: 80px;
    }
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
    height: 220px !important;
    object-fit: contain !important;
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
    color: #e9d736;
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
    color: #e9d736;
    font-weight: bold;
}

.mv-item-infor p.rate i {
    color: #e9d736;
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

/* Review Section Styles */
.mv-user-review-item {
    margin-bottom: 30px;
    padding-bottom: 25px;
    border-bottom: 1px solid rgba(64, 82, 102, 0.3);
}

.mv-user-review-item:last-child {
    border-bottom: none;
}

.mv-user-review-item .user-infor img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

@media (max-width: 768px) {
    .movie-single-ct h1.bd-hd {
        font-size: 1.8rem;
    }
    
    .movie-actions {
        gap: 10px;
        justify-content: center;
    }
    
    .action-btn {
        min-width: 60px;
        padding: 8px 10px;
    }
    
    .action-btn i {
        font-size: 20px;
    }
    
    .action-btn span {
        font-size: 10px;
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
					     alt="{{ $movie['title'] ?? 'Movie Poster' }}"
					     style="max-width: 400px; min-height: 550px; max-height: 650px; width: 100%; height: auto; object-fit: cover; object-position: center top;">
				</div>
			</div>
			<div class="col-md-8 col-sm-12 col-xs-12">
				<div class="movie-single-ct main-content">
					<h1 class="bd-hd">{{ $movie['title'] ?? 'Untitled' }} 
						@if(isset($movie['release_date']))
							<span>{{ date('Y', strtotime($movie['release_date'])) }}</span>
						@endif
					</h1>
					
					<!-- Movie Action Buttons -->
					<div class="movie-actions">
						<a href="#" class="action-btn" id="addMovieBtn" onclick="addMovieToCollection(event)">
							<i class="ion-plus"></i>
							<span>Add</span>
						</a>
						
						<a href="#" class="action-btn" id="likeBtn" onclick="toggleFavorite(event)">
							<i class="ion-heart"></i>
							<span>Like</span>
						</a>
						
						<a href="#" class="action-btn" id="watchlistBtn" onclick="toggleWatchlist(event)">
							<i class="ion-bookmark"></i>
							<span>Watchlist</span>
						</a>
					
						
						<a href="#" class="action-btn" id="reviewBtn" onclick="openReviewModal(event)">
							<i class="ion-compose"></i>
							<span>Review</span>
						</a>
						
						<a href="#" class="action-btn" onclick="shareMovie(event)">
							<i class="ion-android-share-alt"></i>
							<span>Share</span>
						</a>
						
						<a href="{{ $movie['homepage'] ?? '#' }}" class="action-btn" target="_blank" {{ !isset($movie['homepage']) || !$movie['homepage'] ? 'onclick="alert(\'Official site not available for this movie\'); return false;"' : '' }}>
							<i class="ion-link"></i>
							<span>Site</span>
						</a>
                        
                        
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
							<div class="star-rating">
								@for($i = 1; $i <= 10; $i++)
									<i class="ion-ios-star-outline star-icon" data-rating="{{ $i }}" onclick="rateMovie({{ $i }})"></i>
								@endfor
							</div>
							<span id="currentRating" style="margin-left: 10px; color: #dcf836;"></span>
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
						            	<!-- Full-width movie description -->
						            	<div class="col-md-12 col-sm-12 col-xs-12">
						            		<p style="margin-bottom: 40px; line-height: 1.8; font-size: 15px;">{{ $movie['overview'] ?? 'No overview available for this movie.' }}</p>
						            	</div>
						            	
						            	<!-- Movie details in 3 columns -->
						            	<div class="col-md-4 col-sm-6 col-xs-12">
						            	    @if(isset($credits['crew']))
						            			@php 
						            				$director = collect($credits['crew'])->firstWhere('job', 'Director');
						            			@endphp
						            		@endif
						            		
						            		@if($director)
						            		<div class="sb-it">
						            			<h6>Director:</h6>
						            			<p class="cast-names">
						            				<span class="name-badge"><a href="{{ route('celebrities.show', ['id' => $director['id']]) }}">{{ $director['name'] }}</a></span>
						            			</p>
						            		</div>
						            		@endif
						            		
						            		@if(isset($credits['cast']) && count($credits['cast']) > 0)
						            		<div class="sb-it">
						            			<h6>Stars:</h6>
						            			<p class="cast-names">
						            				@foreach(array_slice($credits['cast'], 0, 5) as $actor)
						            					<span class="name-badge"><a href="{{ route('celebrities.show', ['id' => $actor['id']]) }}">{{ $actor['name'] }}</a></span>
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
						            	</div>
						            	
						            	<div class="col-md-4 col-sm-6 col-xs-12">
						            	    @if(isset($credits['crew']))
						            			@php 
						            				$writers = collect($credits['crew'])->where('department', 'Writing');
						            			@endphp
						            		@endif
						            		
						            		@if($writers->isNotEmpty())
						            		<div class="sb-it">
						            			<h6>Writers:</h6>
						            			<p class="cast-names">
						            				@foreach($writers->take(3) as $writer)
						            					@php $writerId = $writer['id']; @endphp
						            					<span class="name-badge"><a href="{{ route('celebrities.show', $writerId) }}">{{ $writer['name'] }}</a></span>
						            				@endforeach
						            			</p>
						            		</div>
						            		@endif
						            		
						            		@if(isset($movie['genres']) && count($movie['genres']) > 0)
						            		<div class="sb-it">
						            			<h6>Genres:</h6>
						            			<p class="cast-names">
						            				@foreach($movie['genres'] as $genre)
						            					@php $genreId = $genre['id']; @endphp
						            					<span class="name-badge"><a href="{{ route('movies.genre', $genreId) }}">{{ $genre['name'] }}</a></span>
						            				@endforeach
						            			</p>
						            		</div>
						            		@endif
						            		
						            		@if(isset($movie['runtime']) && $movie['runtime'])
						            		<div class="sb-it">
						            			<h6>Run Time:</h6>
						            			<p>{{ $movie['runtime'] }} min</p>
						            		</div>
						            		@endif
						            	</div>
						            	
						            	<div class="col-md-4 col-sm-12 col-xs-12">
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
						            	</div>
						            </div>
						        </div>
						        <div id="reviews" class="tab">
						           <div class="row">
						            	<div class="topbar-filter">
											<p>Found <span>{{ isset($reviews['total_results']) ? $reviews['total_results'] : 0 }} reviews</span> in total</p>
											<label>Filter by:</label>
											<select>
												<option value="newest">Newest First</option>
												<option value="oldest">Oldest First</option>
												<option value="helpful">Most Helpful</option>
											</select>
										</div>
										
										@if(isset($reviews['results']) && count($reviews['results']) > 0)
											@foreach($reviews['results'] as $review)
											<div class="mv-user-review-item">
												<div class="user-infor">
													@if(isset($review['author_details']['avatar_path']))
														@php
															$avatarPath = $review['author_details']['avatar_path'];
															// Handle Gravatar URLs
															if (str_starts_with($avatarPath, '/https://') || str_starts_with($avatarPath, '/http://')) {
																$avatarUrl = substr($avatarPath, 1); // Remove leading slash
															} elseif (str_starts_with($avatarPath, '/')) {
																$avatarUrl = 'https://image.tmdb.org/t/p/w200' . $avatarPath;
															} else {
																$avatarUrl = asset('images/uploads/userava1.jpg');
															}
														@endphp
														<img src="{{ $avatarUrl }}" alt="{{ $review['author'] }}" onerror="this.src='{{ asset('images/uploads/userava1.jpg') }}'">
													@else
														<img src="{{ asset('images/uploads/userava1.jpg') }}" alt="{{ $review['author'] }}">
													@endif
													<div>
														<h3>{{ $review['author'] }}</h3>
														@if(isset($review['author_details']['rating']) && $review['author_details']['rating'])
														<div class="no-star">
															@php
																$rating = $review['author_details']['rating'];
																$fullStars = floor($rating);
															@endphp
															@for($i = 1; $i <= 10; $i++)
																@if($i <= $fullStars)
																	<i class="ion-android-star"></i>
																@else
																	<i class="ion-android-star last"></i>
																@endif
															@endfor
														</div>
														@endif
														<p class="time">
															{{ date('j F Y', strtotime($review['created_at'])) }} by <a href="{{ $review['url'] ?? '#' }}" target="_blank">{{ $review['author'] }}</a>
														</p>
													</div>
												</div>
												<p>
													@php
														$content = $review['content'];
														// Limit content to 500 characters for preview
														$isLong = strlen($content) > 500;
														$shortContent = $isLong ? substr($content, 0, 500) . '...' : $content;
													@endphp
													{{ $shortContent }}
													@if($isLong)
														<a href="{{ $review['url'] ?? '#' }}" target="_blank" style="color: #dcf836;">Read full review</a>
													@endif
												</p>
											</div>
											@endforeach
										@else
											<div class="mv-user-review-item">
												<p style="text-align: center; color: #abb7c4; padding: 40px 0;">No reviews available for this movie yet.</p>
											</div>
										@endif
						            </div>
						        </div>
						        <div id="cast" class="tab">
						        	<div class="row">
						            	<div class="col-md-12">
						            		<h3>Cast & Crew of</h3>
					       	 				<h2 style="margin-bottom: 30px;">{{ $movie['title'] ?? 'This Movie' }}</h2>
						            	</div>
						            	
						            	<!-- Two Column Layout -->
						            	<div class="col-md-6 col-sm-12 col-xs-12">
						            		@if(isset($credits['cast']) && count($credits['cast']) > 0)
						            		<div class="sb-it">
						            			<h6>Cast:</h6>
						            			<p class="cast-names">
						            				@foreach(array_slice($credits['cast'], 0, 20) as $actor)
						            					@php $actorId = $actor['id']; @endphp
						            					<span class="name-badge"><a href="{{ route('celebrities.show', $actorId) }}">{{ $actor['name'] }}</a></span>
						            				@endforeach
						            			</p>
						            		</div>
						            		@endif
						            	</div>
						            	
						            	<div class="col-md-6 col-sm-12 col-xs-12">
						            		@if(isset($credits['crew']))
						            		<div class="sb-it">
						            			<h6>Directors & Writers:</h6>
						            			<p class="cast-names">
						            				@foreach(collect($credits['crew'])->whereIn('job', ['Director', 'Writer', 'Screenplay'])->unique('name')->take(15) as $crew)
						            					@php $crewId = $crew['id']; @endphp
						            					<span class="name-badge"><a href="{{ route('celebrities.show', $crewId) }}">{{ $crew['name'] }}</a></span>
						            				@endforeach
						            			</p>
						            		</div>
						            		@endif
						            		
						            		@if(isset($credits['crew']))
						            		@php
						            			$producers = collect($credits['crew'])->where('job', 'Producer')->unique('name')->take(10);
						            		@endphp
						            		@if($producers->isNotEmpty())
						            		<div class="sb-it">
						            			<h6>Producers:</h6>
						            			<p class="cast-names">
						            				@foreach($producers as $producer)
						            					<span class="name-badge"><a href="{{ route('celebrities.show', ['id' => $producer['id']]) }}">{{ $producer['name'] }}</a></span>
						            				@endforeach
						            			</p>
						            		</div>
						            		@endif
						            		@endif
						            		
						            		@if(isset($credits['crew']))
						            		@php
						            			$composers = collect($credits['crew'])->where('job', 'Original Music Composer')->unique('name')->take(5);
						            		@endphp
						            		@if($composers->isNotEmpty())
						            		<div class="sb-it">
						            			<h6>Music:</h6>
						            			<p class="cast-names">
						            				@foreach($composers as $composer)
						            					<span class="name-badge"><a href="{{ route('celebrities.show', ['id' => $composer['id']]) }}">{{ $composer['name'] }}</a></span>
						            				@endforeach
						            			</p>
						            		</div>
						            		@endif
						            		@endif
						            	</div>
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
													<a class="fancybox-media" href="https://www.youtube.com/embed/{{ $video['key'] }}"><img src="{{ asset('images/uploads/play-vd.png') }}" alt=""></a>
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
											<p>Found <span>{{ $relatedMovies->count() ?? 0 }} movies</span> in total</p>
											<label>Filter by:</label>
											<select id="relatedMoviesFilter">
												<option value="all">All Related</option>
												<option value="rating">Highest Rated</option>
												<option value="recent">Most Recent</option>
											</select>
										</div>
										
										@if($relatedMovies && $relatedMovies->count() > 0)
											<div id="relatedMoviesContainer">
												@foreach($relatedMovies as $related)
												<div class="movie-item-style-2" data-rating="{{ $related['vote_average'] ?? 0 }}" data-date="{{ $related['release_date'] ?? '' }}">
													<img src="{{ app('App\Services\MovieService')->getImageUrl($related['poster_path'], 'w300') }}" alt="{{ $related['title'] }}">
													<div class="mv-item-infor">
														<h6><a href="{{ route('movies.show', ['id' => $related['id']]) }}">{{ $related['title'] }} <span>({{ isset($related['release_date']) ? date('Y', strtotime($related['release_date'])) : 'N/A' }})</span></a></h6>
														<p class="rate"><i class="ion-android-star"></i><span>{{ number_format($related['vote_average'] ?? 0, 1) }}</span> /10</p>
														<p class="describe">{{ Str::limit($related['overview'] ?? 'No description available.', 200) }}</p>
														<p class="run-time">Release: {{ isset($related['release_date']) ? date('j M Y', strtotime($related['release_date'])) : 'Unknown' }}</p>
														@if(isset($related['genre_ids']) && is_array($related['genre_ids']))
															@php
																$genresData = app('App\Services\MovieService')->getGenres();
																$allGenres = $genresData['genres'] ?? [];
																$movieGenres = collect($allGenres)->whereIn('id', array_slice($related['genre_ids'], 0, 3));
															@endphp
															@if($movieGenres->count() > 0)
															<p>Genres: 
																@foreach($movieGenres as $genre)
																	<a href="{{ route('movies.genre', ['genreId' => $genre['id']]) }}">{{ $genre['name'] }}</a>@if(!$loop->last), @endif
																@endforeach
															</p>
															@endif
														@endif
													</div>
												</div>
												@endforeach
											</div>
										@else
											<div class="no-results" style="text-align: center; padding: 40px 0; color: #abb7c4;">
												<p>No related movies found at the moment.</p>
											</div>
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

// Movie action functions
function toggleFavorite() {
    // Toggle favorite functionality
    const btn = event.target.closest('.action-btn');
    const icon = btn.querySelector('i');
    
    if (icon.classList.contains('ion-heart')) {
        icon.classList.remove('ion-heart');
        icon.classList.add('ion-heart-broken');
        console.log('Added to favorites');
    } else {
        icon.classList.remove('ion-heart-broken');
        icon.classList.add('ion-heart');
        console.log('Removed from favorites');
    }
}

function toggleWatchlist() {
    // Toggle watchlist functionality
    console.log('Watchlist toggled');
    alert('Added to watchlist!');
}

function showActivity() {
    // Show user activity
    console.log('Show activity clicked');
    alert('Feature coming soon!');
}

function writeReview() {
    // Navigate to review section
    document.querySelector('#reviews').scrollIntoView({ behavior: 'smooth' });
    document.querySelector('a[href="#reviews"]').click();
}

function addToList() {
    // Add to custom lists
    console.log('Add to lists clicked');
    alert('Add to lists feature coming soon!');
}

function shareMovie(event) {
    event.preventDefault();
    // Share movie functionality
    if (navigator.share) {
        navigator.share({
            title: document.querySelector('.bd-hd').textContent,
            url: window.location.href
        });
    } else {
        // Fallback for browsers that don't support Web Share API
        navigator.clipboard.writeText(window.location.href);
        alert('Movie link copied to clipboard!');
    }
}

// Movie data
const movieData = {
    id: {{ $movie['id'] }},
    title: "{{ $movie['title'] ?? 'Untitled' }}",
    poster: "{{ isset($movie['poster_path']) ? app('App\Services\MovieService')->getImageUrl($movie['poster_path'], 'w500') : '' }}",
    year: {{ isset($movie['release_date']) ? date('Y', strtotime($movie['release_date'])) : 'null' }}
};

let userRating = 0;

// Load user's movie status on page load
document.addEventListener('DOMContentLoaded', function() {
    @auth
        loadMovieStatus();
    @endauth
});

// Load movie status (liked, in watchlist, etc.)
function loadMovieStatus() {
    fetch(`/movies/${movieData.id}/status`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI based on status
            if (data.status.is_liked) {
                document.getElementById('likeBtn').classList.add('active');
            }
            if (data.status.in_watchlist) {
                document.getElementById('watchlistBtn').classList.add('active');
            }
            if (data.status.in_collection) {
                document.getElementById('addMovieBtn').classList.add('active');
                document.getElementById('addMovieBtn').querySelector('span').textContent = 'Added';
            }
        }
    })
    .catch(error => console.error('Error loading movie status:', error));
}

// Rate movie
function rateMovie(rating) {
    @guest
        alert('Please login to rate this movie');
        return;
    @endguest
    
    userRating = rating;
    
    // Update star display
    const stars = document.querySelectorAll('.star-icon');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('ion-ios-star-outline');
            star.classList.add('ion-ios-star');
        } else {
            star.classList.remove('ion-ios-star');
            star.classList.add('ion-ios-star-outline');
        }
    });
}

// Add movie to collection
function addMovieToCollection(event) {
    event.preventDefault();
    
    @guest
        alert('Please login to add movies to your collection');
        return;
    @endguest
    
    if (userRating === 0) {
        alert('Please rate this movie first (1-10 stars)');
        return;
    }
    
    const data = {
        movie_id: movieData.id,
        movie_title: movieData.title,
        movie_poster: movieData.poster,
        rating: userRating,
        release_year: movieData.year,
        _token: '{{ csrf_token() }}'
    };
    
    fetch('/movies/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            document.getElementById('addMovieBtn').classList.add('active');
            document.getElementById('addMovieBtn').querySelector('span').textContent = 'Added';
        } else {
            alert(data.message || 'Failed to add movie');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to add movie to collection');
    });
}

// Toggle like
function toggleFavorite(event) {
    event.preventDefault();
    
    @guest
        alert('Please login to like movies');
        return;
    @endguest
    
    const data = {
        movie_id: movieData.id,
        _token: '{{ csrf_token() }}'
    };
    
    fetch('/movies/like', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const likeBtn = document.getElementById('likeBtn');
            if (data.liked) {
                likeBtn.classList.add('active');
            } else {
                likeBtn.classList.remove('active');
            }
            alert(data.message);
        } else {
            alert(data.message || 'Failed to toggle like');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to toggle like');
    });
}

// Toggle watchlist
function toggleWatchlist(event) {
    event.preventDefault();
    
    @guest
        alert('Please login to add movies to watchlist');
        return;
    @endguest
    
    const data = {
        movie_id: movieData.id,
        movie_title: movieData.title,
        movie_poster: movieData.poster,
        release_year: movieData.year,
        _token: '{{ csrf_token() }}'
    };
    
    fetch('/movies/watchlist', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const watchlistBtn = document.getElementById('watchlistBtn');
            if (data.in_watchlist) {
                watchlistBtn.classList.add('active');
            } else {
                watchlistBtn.classList.remove('active');
            }
            alert(data.message);
        } else {
            alert(data.message || 'Failed to toggle watchlist');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to toggle watchlist');
    });
}

// Open review modal
function openReviewModal(event) {
    event.preventDefault();
    
    @guest
        alert('Please login to write a review');
        return;
    @endguest
    
    if (userRating === 0) {
        alert('Please rate this movie first (1-10 stars)');
        return;
    }
    
    // Create modal
    const modal = document.createElement('div');
    modal.id = 'reviewModal';
    modal.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;';
    
    modal.innerHTML = `
        <div style="background: #0b1a2a; padding: 30px; border-radius: 10px; max-width: 600px; width: 90%;">
            <h2 style="color: #dcf836; margin-bottom: 20px;">Write Your Review</h2>
            <p style="color: #fff; margin-bottom: 15px;">Your Rating: ${userRating}/10</p>
            
            <div style="margin-bottom: 20px;">
                <label style="color: #fff; display: block; margin-bottom: 10px;">
                    <input type="checkbox" id="watchedBefore" style="margin-right: 10px;">
                    Have you watched this movie before?
                </label>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="color: #fff; display: block; margin-bottom: 10px;">Your Review:</label>
                <textarea id="reviewText" rows="6" style="width: 100%; padding: 10px; background: #020d18; color: #fff; border: 1px solid #405266; border-radius: 5px; font-family: inherit;" placeholder="Share your thoughts about this movie... (minimum 10 characters)"></textarea>
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button onclick="closeReviewModal()" style="padding: 10px 20px; background: #405266; color: #fff; border: none; border-radius: 5px; cursor: pointer;">Cancel</button>
                <button onclick="submitReview()" style="padding: 10px 20px; background: #eb70ac; color: #fff; border: none; border-radius: 5px; cursor: pointer;">Submit Review</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
}

function closeReviewModal() {
    const modal = document.getElementById('reviewModal');
    if (modal) {
        modal.remove();
    }
}

function submitReview() {
    const reviewText = document.getElementById('reviewText').value.trim();
    const watchedBefore = document.getElementById('watchedBefore').checked;
    
    if (reviewText.length < 10) {
        alert('Please write at least 10 characters for your review');
        return;
    }
    
    const data = {
        movie_id: movieData.id,
        movie_title: movieData.title,
        movie_poster: movieData.poster,
        rating: userRating,
        watched_before: watchedBefore,
        review: reviewText,
        release_year: movieData.year,
        _token: '{{ csrf_token() }}'
    };
    
    fetch('/movies/review', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeReviewModal();
        } else {
            alert(data.message || 'Failed to submit review');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to submit review');
    });
}

// Related movies filter
document.addEventListener('DOMContentLoaded', function() {
    const filterSelect = document.getElementById('relatedMoviesFilter');
    if (filterSelect) {
        filterSelect.addEventListener('change', function() {
            const container = document.getElementById('relatedMoviesContainer');
            const movies = Array.from(container.querySelectorAll('.movie-item-style-2'));
            const filterValue = this.value;
            
            // Sort movies based on filter
            movies.sort((a, b) => {
                if (filterValue === 'rating') {
                    return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
                } else if (filterValue === 'recent') {
                    return new Date(b.dataset.date) - new Date(a.dataset.date);
                }
                return 0; // 'all' - keep original order
            });
            
            // Re-append sorted movies
            if (filterValue !== 'all') {
                movies.forEach(movie => container.appendChild(movie));
            }
        });
    }
});
</script>

<style>
.action-btn.active {
    color: #eb70ac !important;
}

.action-btn.active i {
    color: #eb70ac !important;
}

.star-icon {
    cursor: pointer;
    transition: all 0.3s ease;
    color: #dcf836;
}

.star-icon:hover {
    transform: scale(1.2);
}

.ion-ios-star {
    color: #dcf836 !important;
}

.ion-ios-star-outline {
    color: #405266 !important;
}

/* Ensure no white background anywhere on the page */
footer, .footer, .ht-footer {
    background: #020d18 !important;
}

/* Prevent any white gaps */
.row, .container, .col-md-12, .col-md-8, .col-md-4 {
    background: transparent !important;
}
</style>
@endpush