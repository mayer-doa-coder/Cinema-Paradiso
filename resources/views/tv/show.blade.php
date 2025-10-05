@extends('layouts.app')

@section('title', $tvShow['name'] ?? 'TV Show Details')

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
<!-- TV Show Hero Section -->
<div class="hero mv-single-hero" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ $tvShow['backdrop_url'] ?? asset('images/uploads/movie-single.jpg') }}') no-repeat center center; background-size: cover;">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<!-- Movie Details -->
				<div class="movie-single-ct main-content">
					<h1 class="bd-hd">{{ $tvShow['name'] ?? 'TV Show' }} 
						<span>{{ isset($tvShow['first_air_date']) ? \Carbon\Carbon::parse($tvShow['first_air_date'])->format('Y') : '' }}</span>
					</h1>
					<div class="social-btn">
						<a href="#" class="parent-btn"><i class="ion-heart"></i> Add to Favorites</a>
						<a href="#" class="parent-btn"><i class="ion-share-alt"></i> Share</a>
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
							<p><span>{{ number_format($tvShow['vote_average'] ?? 0, 1) }}</span> /10<br>
								<span class="rv">{{ $tvShow['vote_count'] ?? 0 }} Reviews</span>
							</p>
						</div>
						<div class="rate-star">
							<p>Rate This TV Show: </p>
							@for($i = 1; $i <= 10; $i++)
								<i class="ion-ios-star-outline"></i>
							@endfor
						</div>
					</div>
					<div class="movie-tabs">
						<div class="tabs">
							<ul class="tab-links tabs-mv">
								<li class="active"><a href="#overview">Overview</a></li>
								<li><a href="#reviews">Reviews</a></li>
								<li><a href="#cast">Cast</a></li>
								<li><a href="#media">Media</a></li>
							</ul>
						    <div class="tab-content">
						        <div id="overview" class="tab active">
						            <div class="row">
						            	<div class="col-md-8 col-sm-12 col-xs-12">
						            		<p>{{ $tvShow['overview'] ?? 'No overview available.' }}</p>
						            		<div class="title-hd-sm">
						            			<h4>Seasons & Episodes</h4>
						            		</div>
						            		@if(isset($tvShow['seasons']) && count($tvShow['seasons']) > 0)
						            		<div class="seasons-list">
						            			@foreach($tvShow['seasons'] as $season)
						            			@if($season['season_number'] > 0)
						            			<div class="season-item">
						            				<div class="season-poster">
						            					<img src="{{ $season['poster_url'] ?? asset('images/uploads/movie-placeholder.jpg') }}" alt="Season {{ $season['season_number'] }}" width="60" height="90">
						            				</div>
						            				<div class="season-info">
						            					<h5><a href="{{ route('tv.season', [$tvShow['id'], $season['season_number']]) }}">Season {{ $season['season_number'] }}</a></h5>
						            					<p>{{ $season['episode_count'] ?? 0 }} Episodes</p>
						            					@if(isset($season['air_date']))
						            					<p>Aired: {{ \Carbon\Carbon::parse($season['air_date'])->format('M Y') }}</p>
						            					@endif
						            				</div>
						            			</div>
						            			@endif
						            			@endforeach
						            		</div>
						            		@else
						            		<p>No season information available.</p>
						            		@endif
						            	</div>
						            	<div class="col-md-4 col-xs-12 col-sm-12">
						            		<div class="sb-it">
						            			<h6>Full Name</h6>
						            			<p>{{ $tvShow['original_name'] ?? $tvShow['name'] ?? 'N/A' }}</p>
						            		</div>
						            		<div class="sb-it">
						            			<h6>First Air Date</h6>
						            			<p>{{ isset($tvShow['first_air_date']) ? \Carbon\Carbon::parse($tvShow['first_air_date'])->format('M d, Y') : 'N/A' }}</p>
						            		</div>
						            		@if(isset($tvShow['last_air_date']) && $tvShow['last_air_date'])
						            		<div class="sb-it">
						            			<h6>Last Air Date</h6>
						            			<p>{{ \Carbon\Carbon::parse($tvShow['last_air_date'])->format('M d, Y') }}</p>
						            		</div>
						            		@endif
						            		<div class="sb-it">
						            			<h6>Status</h6>
						            			<p>{{ $tvShow['status'] ?? 'Unknown' }}</p>
						            		</div>
						            		<div class="sb-it">
						            			<h6>Genres</h6>
						            			<p>
						            				@if(isset($tvShow['genres']) && count($tvShow['genres']) > 0)
						            					@foreach($tvShow['genres'] as $genre)
						            						<a href="{{ route('tv.genre', $genre['id']) }}">{{ $genre['name'] }}</a>{{ !$loop->last ? ', ' : '' }}
						            					@endforeach
						            				@else
						            					N/A
						            				@endif
						            			</p>
						            		</div>
						            		@if(isset($tvShow['networks']) && count($tvShow['networks']) > 0)
						            		<div class="sb-it">
						            			<h6>Networks</h6>
						            			<p>
						            				@foreach($tvShow['networks'] as $network)
						            					{{ $network['name'] }}{{ !$loop->last ? ', ' : '' }}
						            				@endforeach
						            			</p>
						            		</div>
						            		@endif
						            		<div class="sb-it">
						            			<h6>Number of Seasons</h6>
						            			<p>{{ $tvShow['number_of_seasons'] ?? 'N/A' }}</p>
						            		</div>
						            		<div class="sb-it">
						            			<h6>Number of Episodes</h6>
						            			<p>{{ $tvShow['number_of_episodes'] ?? 'N/A' }}</p>
						            		</div>
						            	</div>
						            </div>
						        </div>
						        <div id="reviews" class="tab review">
						           <div class="row">
						            	<div class="rv-hd">
						            		<div class="div">
							            		<h3>Related TV Shows</h3>
							       			</div>
							       			@if(isset($similar) && count($similar) > 0)
							       			<div class="topbar-filter">
								            	<p>Found <span>{{ count($similar) }} similar TV shows</span> in total</p>
								            </div>
								            @endif
						            	</div>
						            	@forelse($similar ?? [] as $similarShow)
						            	<div class="mv-item-infor">
						            		<h6><a href="{{ route('tv.show', $similarShow['id']) }}">{{ $similarShow['name'] }}</a></h6>
						            		<p class="rate"><i class="ion-android-star"></i><span>{{ number_format($similarShow['vote_average'], 1) }}</span> /10</p>
						            		<p class="describe">{{ Str::limit($similarShow['overview'], 150) }}</p>
						            		<p class="run-time">First Aired: {{ isset($similarShow['first_air_date']) ? \Carbon\Carbon::parse($similarShow['first_air_date'])->format('M d, Y') : 'Unknown' }}</p>
						            	</div>
						            	@empty
						            	<p>No similar TV shows found.</p>
						            	@endforelse
						            </div>
						        </div>
						        <div id="cast" class="tab">
						        	<div class="row">
						            	<h3>Cast & Crew</h3>
						            	@forelse($cast ?? [] as $castMember)
						            	<div class="cast-it">
						            		<div class="cast-left">
						            			<img src="{{ $castMember['profile_url'] ?? asset('images/uploads/default-avatar.jpg') }}" alt="{{ $castMember['name'] }}" width="70" height="70">
						            			<a href="#">{{ $castMember['name'] }}</a>
						            		</div>
						            		<p>{{ $castMember['character'] ?? 'Actor' }}</p>
						            	</div>
						            	@empty
						            	<p>No cast information available.</p>
						            	@endforelse
						            </div>
						        </div>
						        <div id="media" class="tab">
						        	<div class="row">
						            	<div class="rv-hd">
						            		<div>
						            			<h3>Videos & Trailers</h3>
						            		</div>
						            	</div>
						            	@forelse($videos ?? [] as $video)
						            	<div class="title-hd-sm">
						            		<h4>{{ $video['name'] ?? 'Video' }}</h4>
						            	</div>
						            	<iframe width="100%" height="315" src="https://www.youtube.com/embed/{{ $video['key'] }}" frameborder="0" allowfullscreen></iframe>
						            	@empty
						            	<p>No videos available.</p>
						            	@endforelse
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

<!-- Similar TV Shows Section -->
@if(isset($recommendations) && count($recommendations) > 0)
<div class="movie-items">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="title-hd">
					<h2>You may also like...</h2>
				</div>
				<div class="tabs">
					<div class="tab-content">
						<div id="tab1" class="tab active">
							<div class="row">
								<div class="slick-multiItemSlider">
									@foreach($recommendations as $recommendation)
									<div class="slide-it">
										<div class="movie-item">
											<div class="mv-img">
												<a href="{{ route('tv.show', $recommendation['id']) }}">
													<img src="{{ $recommendation['poster_url'] ?? asset('images/uploads/movie-placeholder.jpg') }}" alt="{{ $recommendation['name'] }}" width="185" height="284">
												</a>
											</div>
											<div class="hvr-inner">
												<a href="{{ route('tv.show', $recommendation['id']) }}">Read more <i class="ion-android-arrow-dropright"></i></a>
											</div>
											<div class="title-in">
												<h6><a href="{{ route('tv.show', $recommendation['id']) }}">{{ Str::limit($recommendation['name'], 20) }}</a></h6>
												<p><i class="ion-android-star"></i><span>{{ number_format($recommendation['vote_average'], 1) }}</span> /10</p>
											</div>
										</div>
									</div>
									@endforeach
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endif
@endsection

@push('scripts')
<script>
$(document).ready(function(){
    // Initialize slick carousel for recommendations
    if ($('.slick-multiItemSlider').length) {
        $('.slick-multiItemSlider').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    }
});
</script>
@endpush