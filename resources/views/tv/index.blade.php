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
							</a>
						</li>
						<li class="dropdown first">
							<a class="btn btn-default dropdown-toggle lv1" data-toggle="dropdown" data-hover="dropdown">
							TV Shows <i class="fa fa-angle-down" aria-hidden="true"></i>
							</a>
							<ul class="dropdown-menu level1">
								<li><a href="{{ route('tv.index', ['category' => 'popular']) }}">Popular</a></li>
								<li><a href="{{ route('tv.index', ['category' => 'top-rated']) }}">Top Rated</a></li>
								<li><a href="{{ route('tv.index', ['category' => 'trending']) }}">Trending</a></li>
								<li><a href="{{ route('tv.index', ['category' => 'airing-today']) }}">Airing Today</a></li>
								<li><a href="{{ route('tv.index', ['category' => 'on-air']) }}">On Air</a></li>
							</ul>
						</li>
						<li class="dropdown first">
							<a class="btn btn-default dropdown-toggle lv1" data-toggle="dropdown" data-hover="dropdown">
							celebrities <i class="fa fa-angle-down" aria-hidden="true"></i>
							</a>
							<ul class="dropdown-menu level1">
								<li><a href="{{ route('celebrities') }}">Celebrity grid</a></li>
								<li><a href="{{ route('celebritygrid01') }}">Celebrity grid 2</a></li>
								<li><a href="{{ route('celebritygrid02') }}">Celebrity grid 3</a></li>
								<li class="it-last"><a href="{{ route('celebritylist') }}">Celebrity list</a></li>
							</ul>
						</li>
						<li class="dropdown first">
							<a class="btn btn-default dropdown-toggle lv1" data-toggle="dropdown" data-hover="dropdown">
							news <i class="fa fa-angle-down" aria-hidden="true"></i>
							</a>
							<ul class="dropdown-menu level1">
								<li><a href="{{ route('blog') }}">blog</a></li>
								<li><a href="{{ route('bloggrid') }}">blog Grid</a></li>
								<li class="it-last"><a href="{{ route('blogdetail') }}">blog Detail</a></li>
							</ul>
						</li>
						<li class="dropdown first">
							<a class="btn btn-default dropdown-toggle lv1" data-toggle="dropdown" data-hover="dropdown">
							community <i class="fa fa-angle-down" aria-hidden="true"></i>
							</a>
							<ul class="dropdown-menu level1">
								<li><a href="{{ route('community') }}">Community</a></li>
							</ul>
						</li>
					</ul>
					<ul class="nav navbar-nav flex-child-menu menu-right">
						<li class="dropdown first">
							<a class="btn btn-default dropdown-toggle lv1" data-toggle="dropdown" data-hover="dropdown">
							<i class="fa fa-search" aria-hidden="true"></i>
							</a>
							<ul class="dropdown-menu level1">
								<li class="form-container">
									<form class="form-group" action="{{ route('tv.search') }}" method="GET">
										<input type="text" name="q" class="form-control" placeholder="Search TV shows..." value="{{ request('q') }}">
									</form>
								</li>
							</ul>
						</li>
						<li class="loginLink">
							<a href="#" id="openModal">LOG In</a>
						</li>
						<li class="btn signupLink">
							<a href="#" id="openModal">sign up</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
	</div>
</header>
<!-- END | Header -->

<div class="hero common-hero">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="hero-ct">
					<h1>{{ $title }}</h1>
					<ul class="breadcumb">
						<li class="active"><a href="{{ route('home') }}">Home</a></li>
						<li><span class="ion-ios-arrow-right"></span>TV Shows</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="page-single movie_list">
	<div class="container">
		<div class="row ipad-width2">
			<div class="col-md-8 col-sm-12 col-xs-12">
				<div class="topbar-filter">
					<p>Found <span id="pagination-info">{{ count($tvShows) }} TV shows</span> in total</p>
					<label>Sort by:</label>
					<select id="category-filter" onchange="window.location.href=this.value">
						<option value="{{ route('tv.index', ['category' => 'popular']) }}" {{ $currentCategory == 'popular' ? 'selected' : '' }}>Popularity Descending</option>
						<option value="{{ route('tv.index', ['category' => 'top-rated']) }}" {{ $currentCategory == 'top-rated' ? 'selected' : '' }}>Rating Descending</option>
						<option value="{{ route('tv.index', ['category' => 'trending']) }}" {{ $currentCategory == 'trending' ? 'selected' : '' }}>Trending</option>
						<option value="{{ route('tv.index', ['category' => 'airing-today']) }}" {{ $currentCategory == 'airing-today' ? 'selected' : '' }}>Airing Today</option>
						<option value="{{ route('tv.index', ['category' => 'on-air']) }}" {{ $currentCategory == 'on-air' ? 'selected' : '' }}>On Air</option>
					</select>
				</div>
				<div class="flex-wrap-movielist">
					@forelse($tvShows as $tvShow)
					<div class="movie-item-style-2 movie-item-style-1">
						<img src="{{ $tvShow['poster_url'] ?? asset('images/uploads/movie-placeholder.jpg') }}" alt="{{ $tvShow['name'] ?? 'TV Show' }}">
						<div class="hvr-inner">
							<a href="{{ route('tv.show', $tvShow['id']) }}">Read more <i class="ion-android-arrow-dropright"></i></a>
						</div>
						<div class="mv-item-infor">
							<h6><a href="{{ route('tv.show', $tvShow['id']) }}">{{ $tvShow['name'] ?? 'TV Show' }}</a></h6>
							<p class="rate"><i class="ion-android-star"></i><span>{{ number_format($tvShow['vote_average'] ?? 0, 1) }}</span> /10</p>
							<p class="describe">{{ Str::limit($tvShow['overview'] ?? 'No description available.', 120) }}</p>
							<p class="run-time">First Air Date: {{ isset($tvShow['first_air_date']) ? \Carbon\Carbon::parse($tvShow['first_air_date'])->format('M d, Y') : 'Unknown' }}</p>
							<p class="director">Network: {{ $tvShow['networks'][0]['name'] ?? 'Unknown' }}</p>
						</div>
					</div>
					@empty
					<div class="col-md-12">
						<div class="no-results">
							<h3>No TV shows found</h3>
							<p>{{ $error ?? 'Try adjusting your filters or search terms.' }}</p>
						</div>
					</div>
					@endforelse
				</div>
				
				@if(isset($pagination) && !empty($pagination))
				<div class="topbar-filter">
					<label>Showing {{ count($tvShows) }} of {{ $pagination['total_results'] ?? 0 }} TV shows</label>
					<div class="pagination2">
						<span>Page {{ $pagination['page'] ?? 1 }} of {{ $pagination['total_pages'] ?? 1 }}</span>
						@if(($pagination['page'] ?? 1) > 1)
							<a href="{{ route('tv.index', ['category' => $currentCategory, 'page' => ($pagination['page'] - 1)]) }}"><i class="ion-arrow-left-b"></i></a>
						@endif
						@if(($pagination['page'] ?? 1) < ($pagination['total_pages'] ?? 1))
							<a href="{{ route('tv.index', ['category' => $currentCategory, 'page' => ($pagination['page'] + 1)]) }}"><i class="ion-arrow-right-b"></i></a>
						@endif
					</div>
				</div>
				@endif
			</div>
			<div class="col-md-4 col-sm-12 col-xs-12">
				<div class="sidebar">
					<div class="searh-form">
						<h4 class="sb-title">Search for TV Shows</h4>
						<form class="form-style-1" action="{{ route('tv.search') }}" method="GET">
							<div class="row">
								<div class="col-md-12 form-it">
									<label>TV Show name</label>
									<input type="text" name="q" placeholder="Enter TV show name" value="{{ request('q') }}">
								</div>
								<div class="col-md-12 form-it">
									<label>Genres</label>
									<div class="group-ip">
										<select name="genre" id="genre">
											<option value="">Select Genre</option>
											@foreach($genres ?? [] as $genre)
												<option value="{{ $genre['id'] }}">{{ $genre['name'] }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-12 ">
									<input class="submit" type="submit" value="submit">
								</div>
							</div>
						</form>
					</div>
					<div class="ads">
						@if(isset($randomWallpaper) && !empty($randomWallpaper['backdrop_url']))
							<div class="tv-wallpaper" style="position: relative; width: 336px; height: 296px; overflow: hidden; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.3);">
								<img src="{{ $randomWallpaper['backdrop_url'] }}" alt="{{ $randomWallpaper['name'] ?? 'TV Show Wallpaper' }}" 
									 style="width: 100%; height: 100%; object-fit: cover;">
								<div class="wallpaper-overlay" style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.8)); color: white; padding: 15px;">
									<h5 style="margin: 0; font-size: 14px; font-weight: bold;">{{ $randomWallpaper['name'] ?? 'Featured TV Show' }}</h5>
									@if(!empty($randomWallpaper['overview']))
										<p style="margin: 5px 0 0; font-size: 11px; opacity: 0.9;">{{ Str::limit($randomWallpaper['overview'], 80) }}</p>
									@endif
								</div>
							</div>
						@else
							<img src="{{ asset('images/uploads/ads1.png') }}" alt="" width="336" height="296">
						@endif
					</div>
					<div class="celebrities">
						<h4 class="sb-title">Popular TV Shows</h4>
						@forelse($popular ?? [] as $show)
						<div class="celeb-item">
							<a href="{{ route('tv.show', $show['id']) }}">
								<img src="{{ $show['poster_url'] ?? asset('images/uploads/movie-placeholder.jpg') }}" alt="{{ $show['name'] }}" width="70" height="70">
							</a>
							<div class="celeb-author">
								<h6><a href="{{ route('tv.show', $show['id']) }}">{{ Str::limit($show['name'], 20) }}</a></h6>
								<span>{{ number_format($show['vote_average'], 1) }}/10</span>
							</div>
						</div>
						@empty
						<p>No popular TV shows available.</p>
						@endforelse
						<a href="{{ route('tv.index', ['category' => 'popular']) }}" class="btn">See all TV shows<i class="ion-ios-arrow-right"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle pagination info update
    const paginationInfo = document.getElementById('pagination-info');
    if (paginationInfo) {
        const tvShows = document.querySelectorAll('.movie-item-style-2');
        if (tvShows.length === 0) {
            paginationInfo.textContent = '0 TV shows';
        }
    }
});
</script>
@endpush