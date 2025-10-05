@extends('layouts.app')

@section('title', $genreName . ' TV Shows')

@section('content')
<div class="hero common-hero">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="hero-ct">
					<h1>{{ $genreName }} TV Shows</h1>
					<ul class="breadcumb">
						<li class="active"><a href="{{ route('home') }}">Home</a></li>
						<li><span class="ion-ios-arrow-right"></span>TV Shows</li>
						<li><span class="ion-ios-arrow-right"></span>{{ $genreName }}</li>
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
					<p>Found <span>{{ count($tvShows) }} {{ $genreName }} TV shows</span> in total</p>
					<label>Sort by:</label>
					<select onchange="window.location.href=this.value">
						<option value="{{ route('tv.genre', $genreId) }}">Popularity Descending</option>
						<option value="{{ route('tv.index', ['category' => 'top-rated']) }}">Rating Descending</option>
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
						</div>
					</div>
					@empty
					<div class="col-md-12">
						<div class="no-results">
							<h3>No {{ $genreName }} TV shows found</h3>
							<p>{{ $error ?? 'Try browsing other genres or check back later.' }}</p>
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
							<a href="{{ route('tv.genre', [$genreId, 'page' => ($pagination['page'] - 1)]) }}"><i class="ion-arrow-left-b"></i></a>
						@endif
						@if(($pagination['page'] ?? 1) < ($pagination['total_pages'] ?? 1))
							<a href="{{ route('tv.genre', [$genreId, 'page' => ($pagination['page'] + 1)]) }}"><i class="ion-arrow-right-b"></i></a>
						@endif
					</div>
				</div>
				@endif
			</div>
			<div class="col-md-4 col-sm-12 col-xs-12">
				<div class="sidebar">
					<div class="searh-form">
						<h4 class="sb-title">Browse TV Show Genres</h4>
						<div class="row">
							@foreach($genres ?? [] as $genre)
							<div class="col-md-6 form-it">
								<a href="{{ route('tv.genre', $genre['id']) }}" class="btn-genre {{ $genre['id'] == $genreId ? 'active' : '' }}">
									{{ $genre['name'] }}
								</a>
							</div>
							@endforeach
						</div>
					</div>
					<div class="ads">
						<img src="{{ asset('images/uploads/ads1.png') }}" alt="" width="336" height="296">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('styles')
<style>
.btn-genre {
	display: block;
	padding: 8px 12px;
	margin: 5px 0;
	background: #233a50;
	color: #fff;
	text-decoration: none;
	border-radius: 4px;
	text-align: center;
	transition: all 0.3s;
}
.btn-genre:hover,
.btn-genre.active {
	background: #4280bf;
	color: #fff;
	text-decoration: none;
}
</style>
@endpush