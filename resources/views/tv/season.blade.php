@extends('layouts.app')

@section('title', ($tvShow['name'] ?? 'TV Show') . ' - Season ' . $seasonNumber)

@section('content')
<div class="hero common-hero">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="hero-ct">
					<h1>{{ $tvShow['name'] ?? 'TV Show' }} - Season {{ $seasonNumber }}</h1>
					<ul class="breadcumb">
						<li class="active"><a href="{{ route('home') }}">Home</a></li>
						<li><span class="ion-ios-arrow-right"></span><a href="{{ route('tv.index') }}">TV Shows</a></li>
						<li><span class="ion-ios-arrow-right"></span><a href="{{ route('tv.show', $tvShow['id']) }}">{{ $tvShow['name'] }}</a></li>
						<li><span class="ion-ios-arrow-right"></span>Season {{ $seasonNumber }}</li>
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
				@if(isset($season) && $season)
				<div class="season-header">
					<div class="row">
						<div class="col-md-3">
							<img src="{{ $season['poster_url'] ?? asset('images/uploads/movie-placeholder.jpg') }}" alt="Season {{ $seasonNumber }}" class="season-poster">
						</div>
						<div class="col-md-9">
							<h2>Season {{ $seasonNumber }}</h2>
							@if(isset($season['overview']) && $season['overview'])
							<p>{{ $season['overview'] }}</p>
							@endif
							<p><strong>Episodes:</strong> {{ $season['episode_count'] ?? 0 }}</p>
							@if(isset($season['air_date']))
							<p><strong>Air Date:</strong> {{ \Carbon\Carbon::parse($season['air_date'])->format('M d, Y') }}</p>
							@endif
						</div>
					</div>
				</div>

				<div class="episodes-list">
					<h3>Episodes</h3>
					@if(isset($season['episodes']) && count($season['episodes']) > 0)
					@foreach($season['episodes'] as $episode)
					<div class="episode-item">
						<div class="row">
							<div class="col-md-3">
								<img src="{{ $episode['still_url'] ?? asset('images/uploads/movie-placeholder.jpg') }}" alt="Episode {{ $episode['episode_number'] }}" class="episode-still">
							</div>
							<div class="col-md-9">
								<h4>{{ $episode['episode_number'] }}. {{ $episode['name'] ?? 'Episode ' . $episode['episode_number'] }}</h4>
								@if(isset($episode['overview']) && $episode['overview'])
								<p>{{ $episode['overview'] }}</p>
								@endif
								@if(isset($episode['air_date']))
								<p><strong>Air Date:</strong> {{ \Carbon\Carbon::parse($episode['air_date'])->format('M d, Y') }}</p>
								@endif
								@if(isset($episode['vote_average']) && $episode['vote_average'] > 0)
								<p><strong>Rating:</strong> {{ number_format($episode['vote_average'], 1) }}/10</p>
								@endif
							</div>
						</div>
					</div>
					@endforeach
					@else
					<p>No episode information available for this season.</p>
					@endif
				</div>
				@else
				<div class="no-results">
					<h3>Season not found</h3>
					<p>{{ $error ?? 'The requested season could not be found.' }}</p>
					<a href="{{ route('tv.show', $tvShow['id'] ?? 1) }}" class="btn">Back to TV Show</a>
				</div>
				@endif
			</div>
			<div class="col-md-4 col-sm-12 col-xs-12">
				<div class="sidebar">
					@if(isset($tvShow['seasons']) && count($tvShow['seasons']) > 0)
					<div class="seasons-nav">
						<h4 class="sb-title">All Seasons</h4>
						@foreach($tvShow['seasons'] as $s)
						@if($s['season_number'] > 0)
						<div class="season-nav-item {{ $s['season_number'] == $seasonNumber ? 'active' : '' }}">
							<a href="{{ route('tv.season', [$tvShow['id'], $s['season_number']]) }}">
								<img src="{{ $s['poster_url'] ?? asset('images/uploads/movie-placeholder.jpg') }}" alt="Season {{ $s['season_number'] }}" width="50" height="75">
								<div class="season-nav-info">
									<h6>Season {{ $s['season_number'] }}</h6>
									<p>{{ $s['episode_count'] ?? 0 }} Episodes</p>
								</div>
							</a>
						</div>
						@endif
						@endforeach
					</div>
					@endif
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
.season-poster {
	width: 100%;
	max-width: 200px;
	border-radius: 8px;
}
.episode-item {
	margin-bottom: 30px;
	padding-bottom: 20px;
	border-bottom: 1px solid #eee;
}
.episode-still {
	width: 100%;
	border-radius: 4px;
}
.season-nav-item {
	display: flex;
	align-items: center;
	margin-bottom: 15px;
	padding: 10px;
	border-radius: 4px;
	transition: background 0.3s;
}
.season-nav-item:hover,
.season-nav-item.active {
	background: #f8f9fa;
}
.season-nav-item a {
	display: flex;
	align-items: center;
	text-decoration: none;
	color: inherit;
	width: 100%;
}
.season-nav-item img {
	margin-right: 15px;
	border-radius: 4px;
}
.season-nav-info h6 {
	margin: 0;
	font-weight: bold;
}
.season-nav-info p {
	margin: 0;
	font-size: 12px;
	color: #666;
}
</style>
@endpush