@extends('layouts.app')

@section('title', 'Search TV Shows')

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

<div class="hero common-hero" style="background: url('{{ asset('images/uploads/spider-man-mcu-tr.jpg') }}') no-repeat center center; background-size: cover; margin-top: 20px;">
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
						<img src="{{ $tvShow['poster_url'] ?? asset('images/uploads/movie-placeholder.jpg') }}" 
						     alt="{{ $tvShow['name'] ?? 'TV Show' }}"
						     style="width: 170px; height: 250px; object-fit: cover;">
						<div class="hvr-inner">
							<a href="{{ route('tv.show', $tvShow['id']) }}">Read more <i class="ion-android-arrow-dropright"></i></a>
						</div>
						<div class="mv-item-infor">
							<h6><a href="{{ route('tv.show', $tvShow['id']) }}">{{ $tvShow['name'] ?? 'TV Show' }}</a></h6>
							<p class="rate">
								@php
									$rating = app('App\Services\MovieService')->getRatingStars($tvShow['vote_average'] ?? 0);
								@endphp
								@for($i = 1; $i <= 5; $i++)
									@if($i <= $rating)
										<i class="ion-android-star"></i>
									@else
										<i class="ion-android-star-outline"></i>
									@endif
								@endfor
								<span class="fr">{{ number_format($tvShow['vote_average'] ?? 0, 1) }}/10</span>
							</p>
							@if(isset($tvShow['first_air_date']))
								<p class="time">{{ date('Y', strtotime($tvShow['first_air_date'])) }}</p>
							@endif
							@if(isset($tvShow['overview']))
								<p class="descript">{{ Str::limit($tvShow['overview'], 150) }}</p>
							@endif
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
				</div>
			</div>
		</div>
	</div>
</div>
@endsection