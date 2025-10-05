@extends('layouts.app')

@section('title', 'Search TV Shows')

@section('content')
<div class="hero common-hero">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="hero-ct">
					<h1>TV Show Search Results</h1>
					<ul class="breadcumb">
						<li class="active"><a href="{{ route('home') }}">Home</a></li>
						<li><span class="ion-ios-arrow-right"></span>TV Shows</li>
						<li><span class="ion-ios-arrow-right"></span>Search</li>
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
				@if(!empty($query))
				<div class="topbar-filter">
					<p>Search results for: <strong>"{{ $query }}"</strong></p>
					<p>Found <span>{{ count($tvShows) }} TV shows</span> in total</p>
				</div>
				@endif
				
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
							@if(!empty($query))
								<h3>No TV shows found for "{{ $query }}"</h3>
								<p>Try different keywords or browse popular TV shows instead.</p>
							@else
								<h3>Enter a search term</h3>
								<p>Use the search form to find TV shows.</p>
							@endif
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
							<a href="{{ route('tv.search', ['q' => $query, 'page' => ($pagination['page'] - 1)]) }}"><i class="ion-arrow-left-b"></i></a>
						@endif
						@if(($pagination['page'] ?? 1) < ($pagination['total_pages'] ?? 1))
							<a href="{{ route('tv.search', ['q' => $query, 'page' => ($pagination['page'] + 1)]) }}"><i class="ion-arrow-right-b"></i></a>
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
									<input type="text" name="q" placeholder="Enter TV show name" value="{{ $query ?? '' }}">
								</div>
								<div class="col-md-12 ">
									<input class="submit" type="submit" value="Search">
								</div>
							</div>
						</form>
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