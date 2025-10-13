@extends('layouts.app')

@section('title', $movie['title'] ?? 'Movie Details')

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
    border-radius: 5px;
    font-weight: bold;
    transition: all 0.3s ease;
    margin: 5px;
}

.redbtn {
    background: #dd003f;
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
    border-bottom: 2px solid #233a50;
    display: flex;
    gap: 0;
}

.tabs .tab-links li {
    background: #233a50;
}

.tabs .tab-links li.active {
    background: #dcf836;
}

.tabs .tab-links li a {
    display: block;
    padding: 15px 25px;
    color: #fff;
    text-decoration: none;
    font-weight: bold;
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
    border-bottom: 1px solid #233a50;
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
    border-bottom: 2px solid #233a50;
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
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.cast-it {
    display: flex;
    align-items: center;
    background: #233a50;
    padding: 15px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.cast-it:hover {
    background: #3e9fd8;
    transform: translateY(-2px);
}

.cast-left {
    margin-right: 15px;
}

.cast-left img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
}

.cast-left a {
    color: #fff;
    text-decoration: none;
    font-weight: bold;
    display: block;
    margin-top: 5px;
}

.cast-left a:hover {
    color: #dcf836;
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

/* Responsive Design */
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
    
    .mvcast-item {
        grid-template-columns: 1fr;
    }
    
    .cast-it {
        flex-direction: column;
        text-align: center;
    }
    
    .cast-left {
        margin-right: 0;
        margin-bottom: 10px;
    }
}

.movie-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        45deg,
        rgba(0, 0, 0, 0.7) 0%,
        rgba(0, 0, 0, 0.4) 50%,
        rgba(0, 0, 0, 0.7) 100%
    );
    z-index: 1;
}

.movie-hero .container {
    position: relative;
    z-index: 2;
}

.movie-hero .hero-content {
    text-align: center;
    color: white;
    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.9), 0 0 20px rgba(0, 0, 0, 0.6);
    background: rgba(0, 0, 0, 0.2);
    padding: 20px;
    border-radius: 10px;
    backdrop-filter: blur(2px);
}

.movie-hero h1 {
    font-size: 3.5rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
    line-height: 1.2;
    color: white;
}

.movie-hero .hero-meta {
    font-size: 1.2rem;
    margin-bottom: 1rem;
    opacity: 0.9;
    color: white;
}

.movie-hero .breadcumb {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
}

.movie-hero .breadcumb li {
    font-size: 1rem;
    color: white;
}

.movie-hero .breadcumb a {
    color: #fff;
    text-decoration: none;
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

.movie-hero .breadcumb a:hover {
    opacity: 1;
}

/* Scene Photos Gallery */
.scene-photo-item {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
}

.scene-photo-link:hover img {
    transform: scale(1.05);
}

.scene-photo-item img {
    transition: transform 0.3s ease;
}

@media (max-width: 768px) {
    .movie-hero {
        height: 50vh;
        min-height: 400px;
    }
    
    .movie-hero h1 {
        font-size: 2.5rem;
    }
    
    .movie-hero .hero-meta {
        font-size: 1rem;
    }
}
</style>
@endpush

@section('content')
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
                                    <i class="ion-play"></i></a></div>
                            </div>
                        @endif
                        @if(isset($movie['homepage']) && $movie['homepage'])
                            <div class="btn-transform transform-vertical">
                                <div><a href="{{ $movie['homepage'] }}" target="_blank" class="item item-1 yellowbtn"> 
                                    <i class="ion-card"></i> Official Website</a></div>
                                <div><a href="{{ $movie['homepage'] }}" target="_blank" class="item item-2 yellowbtn"> 
                                    <i class="ion-card"></i></a></div>
                            </div>
                        @endif
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
							<p>Rate This Movie:</p>
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
                                <li><a href="#reviews">Reviews</a></li>
                                <li><a href="#cast">Cast & Crew</a></li>
                                <li><a href="#photos">Scene Photos</a></li>
                                <li><a href="#media">Media</a></li>
                            </ul>
                            
                            <div class="tab-content">
                                <div id="overview" class="tab active">
                                    <div class="row">
                                        <div class="col-md-8 col-sm-12 col-xs-12">
                                            <p>{{ $movie['overview'] ?? 'No overview available.' }}</p>
                                            @if(isset($movie['genres']) && count($movie['genres']) > 0)
                                                <div class="title-hd-sm">
                                                    <h4>Genres</h4>
                                                </div>
                                                <div class="mvcast-item">
                                                    @foreach($movie['genres'] as $genre)
                                                        <div class="cast-it">
                                                            <div class="cast-left">
                                                                <h4>{{ $genre['name'] }}</h4>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="col-md-4 col-xs-12 col-sm-12">
                                            <div class="sb-it">
                                                <h6>Release Date:</h6>
                                                <p>{{ app('App\Services\MovieService')->formatReleaseDate($movie['release_date'] ?? null) }}</p>
                                            </div>
                                            @if(isset($movie['runtime']))
                                                <div class="sb-it">
                                                    <h6>Runtime:</h6>
                                                    <p>{{ app('App\Services\MovieService')->formatRuntime($movie['runtime']) }}</p>
                                                </div>
                                            @endif
                                            @if(isset($movie['budget']) && $movie['budget'] > 0)
                                                <div class="sb-it">
                                                    <h6>Budget:</h6>
                                                    <p>${{ number_format($movie['budget']) }}</p>
                                                </div>
                                            @endif
                                            @if(isset($movie['revenue']) && $movie['revenue'] > 0)
                                                <div class="sb-it">
                                                    <h6>Revenue:</h6>
                                                    <p>${{ number_format($movie['revenue']) }}</p>
                                                </div>
                                            @endif
                                            @if(isset($movie['production_companies']) && count($movie['production_companies']) > 0)
                                                <div class="sb-it">
                                                    <h6>Production:</h6>
                                                    @foreach($movie['production_companies'] as $company)
                                                        <p>{{ $company['name'] }}</p>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div id="reviews" class="tab review">
                                    @if(isset($movie['reviews']) && isset($movie['reviews']['results']) && count($movie['reviews']['results']) > 0)
                                        @foreach(array_slice($movie['reviews']['results'], 0, 3) as $review)
                                            <div class="mv-user-review-item">
                                                <h3>{{ $review['author'] ?? 'Anonymous' }}</h3>
                                                <div class="no-star">
                                                    @if(isset($review['author_details']['rating']))
                                                        @php
                                                            $userRating = $review['author_details']['rating'] / 2; // Convert to 5-star scale
                                                        @endphp
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $userRating)
                                                                <i class="ion-android-star"></i>
                                                            @else
                                                                <i class="ion-android-star-outline"></i>
                                                            @endif
                                                        @endfor
                                                    @endif
                                                </div>
                                                <p class="time">{{ date('F j, Y', strtotime($review['created_at'])) }}</p>
                                                <p>{{ Str::limit($review['content'], 500) }}</p>
                                            </div>
                                        @endforeach
                                    @else
                                        <p>No reviews available for this movie.</p>
                                    @endif
                                </div>

                                <div id="cast" class="tab">
                                    @if($credits && isset($credits['cast']) && count($credits['cast']) > 0)
                                        <div class="row">
                                            <h4>Cast</h4>
                                            @foreach(array_slice($credits['cast'], 0, 12) as $cast)
                                                <div class="col-md-3 col-sm-6 col-xs-12">
                                                    <div class="cast-item">
                                                        <div class="cast-left">
                                                            @if($cast['profile_path'])
                                                                <img src="{{ app('App\Services\MovieService')->getImageUrl($cast['profile_path'], 'w185') }}" 
                                                                     alt="{{ $cast['name'] }}">
                                                            @else
                                                                <img src="{{ asset('images/uploads/author.png') }}" 
                                                                     alt="{{ $cast['name'] }}">
                                                            @endif
                                                            <div class="cast-info">
                                                                <h4>{{ $cast['name'] }}</h4>
                                                                <span>{{ $cast['character'] }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        @if(isset($credits['crew']) && count($credits['crew']) > 0)
                                            <div class="row">
                                                <h4>Key Crew</h4>
                                                @php
                                                    $keyJobs = ['Director', 'Producer', 'Writer', 'Screenplay', 'Executive Producer'];
                                                    $keyCrew = collect($credits['crew'])->whereIn('job', $keyJobs)->take(8);
                                                @endphp
                                                @foreach($keyCrew as $crew)
                                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                                        <div class="cast-item">
                                                            <div class="cast-left">
                                                                @if($crew['profile_path'])
                                                                    <img src="{{ app('App\Services\MovieService')->getImageUrl($crew['profile_path'], 'w185') }}" 
                                                                         alt="{{ $crew['name'] }}">
                                                                @else
                                                                    <img src="{{ asset('images/uploads/author.png') }}" 
                                                                         alt="{{ $crew['name'] }}">
                                                                @endif
                                                                <div class="cast-info">
                                                                    <h4>{{ $crew['name'] }}</h4>
                                                                    <span>{{ $crew['job'] }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @else
                                        <p>Cast and crew information not available.</p>
                                    @endif
                                </div>

                                <div id="photos" class="tab">
                                    @if($images && isset($images['backdrops']) && count($images['backdrops']) > 0)
                                        <div class="title-hd-sm">
                                            <h4>Scene Photos & Backdrops</h4>
                                            <p class="subtitle">High-quality images from the movie</p>
                                        </div>
                                        <div class="row">
                                            @foreach(array_slice($images['backdrops'], 0, 12) as $backdrop)
                                                <div class="col-md-4 col-sm-6 col-xs-12" style="margin-bottom: 20px;">
                                                    <div class="scene-photo-item">
                                                        <a href="{{ app('App\Services\MovieService')->getImageUrl($backdrop['file_path'], 'original') }}" 
                                                           target="_blank" class="scene-photo-link">
                                                            <img src="{{ app('App\Services\MovieService')->getImageUrl($backdrop['file_path'], 'w500') }}" 
                                                                 alt="Scene from {{ $movie['title'] ?? 'Movie' }}"
                                                                 style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.3); transition: transform 0.3s ease;">
                                                        </a>
                                                        <div class="scene-photo-info" style="margin-top: 10px; text-align: center;">
                                                            <small style="color: #abb7c4;">
                                                                {{ $backdrop['width'] }}x{{ $backdrop['height'] }}
                                                                @if(isset($backdrop['vote_average']) && $backdrop['vote_average'] > 0)
                                                                    • ⭐ {{ number_format($backdrop['vote_average'], 1) }}
                                                                @endif
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        @if(count($images['backdrops']) > 12)
                                            <div class="text-center" style="margin-top: 20px;">
                                                <p style="color: #abb7c4;">Showing 12 of {{ count($images['backdrops']) }} available photos</p>
                                            </div>
                                        @endif
                                    @else
                                        <p>No scene photos available for this movie.</p>
                                    @endif
                                </div>

                                <div id="media" class="tab">
                                    @if($videos && isset($videos['results']) && count($videos['results']) > 0)
                                        <div class="title-hd-sm">
                                            <h4>Videos & Trailers</h4>
                                        </div>
                                        <div class="row">
                                            @foreach(array_slice($videos['results'], 0, 6) as $video)
                                                <div class="col-md-4 col-sm-6 col-xs-12">
                                                    <div class="video-item">
                                                        <div class="video-thumb">
                                                            <img src="https://img.youtube.com/vi/{{ $video['key'] }}/hqdefault.jpg" 
                                                                 alt="{{ $video['name'] }}">
                                                            <a href="{{ app('App\Services\MovieService')->getYouTubeUrl($video['key']) }}" 
                                                               target="_blank" class="play-btn">
                                                                <i class="ion-play"></i>
                                                            </a>
                                                        </div>
                                                        <h6>{{ $video['name'] }}</h6>
                                                        <p>{{ $video['type'] }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($images && isset($images['backdrops']) && count($images['backdrops']) > 0)
                                        <div class="title-hd-sm">
                                            <h4>Images</h4>
                                        </div>
                                        <div class="row">
                                            @foreach(array_slice($images['backdrops'], 0, 6) as $image)
                                                <div class="col-md-4 col-sm-6 col-xs-12">
                                                    <div class="gallery-item">
                                                        <a href="{{ app('App\Services\MovieService')->getImageUrl($image['file_path'], 'w1280') }}" 
                                                           class="fancybox" rel="gallery">
                                                            <img src="{{ app('App\Services\MovieService')->getImageUrl($image['file_path'], 'w500') }}" 
                                                                 alt="Movie Image">
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(isset($movie['recommendations']) && isset($movie['recommendations']['results']) && count($movie['recommendations']['results']) > 0)
                        <div class="movie-related">
                            <div class="title-hd-sm">
                                <h4>You might also like</h4>
                            </div>
                            <div class="flex-wrap-movielist">
                                @foreach(array_slice($movie['recommendations']['results'], 0, 4) as $recommended)
                                    <div class="movie-item-style-2 movie-item-style-1">
                                        <img src="{{ app('App\Services\MovieService')->getImageUrl($recommended['poster_path'] ?? null) }}" 
                                             alt="{{ $recommended['title'] ?? 'Movie Poster' }}">
                                        <div class="hvr-inner">
                                            <a href="{{ route('movies.show', $recommended['id']) }}">Read more <i class="ion-android-arrow-dropright"></i></a>
                                        </div>
                                        <div class="mv-item-infor">
                                            <h6><a href="{{ route('movies.show', $recommended['id']) }}">{{ $recommended['title'] ?? 'Untitled' }}</a></h6>
                                            <p class="rate">
                                                @php
                                                    $recRating = app('App\Services\MovieService')->getRatingStars($recommended['vote_average'] ?? 0);
                                                @endphp
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $recRating)
                                                        <i class="ion-android-star"></i>
                                                    @else
                                                        <i class="ion-android-star-outline"></i>
                                                    @endif
                                                @endfor
                                                <span class="fr">{{ number_format($recommended['vote_average'] ?? 0, 1) }}/10</span>
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Movie tabs functionality
    $(document).ready(function() {
        $('.tab-links a').click(function(e) {
            e.preventDefault();
            var currentAttrValue = $(this).attr('href');
            
            $('.tab-content ' + currentAttrValue).show().siblings().hide();
            $(this).parent('li').addClass('active').siblings().removeClass('active');
        });

        // Initialize fancybox for images and videos
        if (typeof $.fancybox !== 'undefined') {
            $('.fancybox').fancybox();
        }
    });
</script>
@endsection