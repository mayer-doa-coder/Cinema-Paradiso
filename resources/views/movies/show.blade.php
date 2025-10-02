@extends('layouts.app')

@section('title', $movie['title'] ?? 'Movie Details')

@section('content')
<div class="hero hero3">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- hero content -->
                <div class="hero-ct">
                    <h1>{{ $movie['title'] ?? 'Movie Details' }}</h1>
                    <ul class="breadcumb">
                        <li class="active"><a href="{{ route('home') }}">Home</a></li>
                        <li><span class="ion-ios-arrow-right"></span><a href="{{ route('movies.index') }}">Movies</a></li>
                        <li><span class="ion-ios-arrow-right"></span>{{ $movie['title'] ?? 'Movie Details' }}</li>
                    </ul>
                </div>
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
                        @if($videos && isset($videos['results']) && count($videos['results']) > 0)
                            @php
                                $trailer = collect($videos['results'])->firstWhere('type', 'Trailer');
                                if (!$trailer) $trailer = $videos['results'][0];
                            @endphp
                            <div class="btn-transform transform-vertical red">
                                <div><a href="{{ app('App\Services\MovieService')->getYouTubeUrl($trailer['key']) }}" 
                                    target="_blank" class="item item-1 redbtn"> <i class="ion-play"></i> Watch Trailer</a></div>
                                <div><a href="{{ app('App\Services\MovieService')->getYouTubeUrl($trailer['key']) }}" 
                                    target="_blank" class="item item-2 redbtn fancybox-media hvr-grow"> 
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
                        <a href="#" class="parent-btn"><i class="ion-plus"></i> Add to Watchlist</a>
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
                        </div>
                        <span class="rate-point">{{ number_format($movie['vote_average'] ?? 0, 1) }}/10</span>
                        <span class="rv">{{ number_format($movie['vote_count'] ?? 0) }} reviews</span>
                    </div>

                    <div class="movie-tabs">
                        <div class="tabs">
                            <ul class="tab-links tabs-mv">
                                <li class="active"><a href="#overview">Overview</a></li>
                                <li><a href="#reviews">Reviews</a></li>
                                <li><a href="#cast">Cast & Crew</a></li>
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