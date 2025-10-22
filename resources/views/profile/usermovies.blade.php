@extends('layouts.app')

@section('title', 'My Movies - Cinema Paradiso')

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
.user-hero {
    background: url('{{ asset('images/uploads/user-bg.jpg') }}') no-repeat center;
    background-size: cover;
    padding: 80px 0;
    position: relative;
}
.user-hero:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(11, 26, 42, 0.8);
}
.user-hero .hero-ct {
    position: relative;
    z-index: 2;
}
.user-information {
    background: #0b1a2a !important;
    padding: 20px !important;
    border-radius: 5px !important;
    border: 1px solid #405266 !important;
    margin-top: 0 !important;
}
.user-img {
    text-align: center !important;
    margin-bottom: 30px !important;
    padding: 0 !important;
}
.user-img img {
    width: 150px !important;
    height: 150px !important;
    border-radius: 50% !important;
    margin-bottom: 15px !important;
    object-fit: cover !important;
    border: 3px solid #e9d736 !important;
}
.user-img .redbtn {
    background: #eb70ac !important;
    color: #fff !important;
    padding: 8px 20px !important;
    border-radius: 5px !important;
    display: inline-block !important;
    transition: all 0.3s ease !important;
    border: none !important;
    cursor: pointer !important;
}
.user-img .redbtn:hover {
    background: #eb70ac !important;
    color: #0b1a2a !important;
}
.user-information ul {
    padding: 0 !important;
}
.user-fav {
    margin-bottom: 20px !important;
    border-top: none !important;
    padding: 0 !important;
}
.user-fav p {
    color: #3e9fd8 !important;
    font-weight: 600 !important;
    font-size: 16px !important;
    text-align: left !important;
    margin-bottom: 10px !important;
    padding-bottom: 10px !important;
    padding-left: 0 !important;
    border-bottom: 1px solid #405266 !important;
    text-transform: none !important;
}
.user-fav ul {
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
}
.user-fav ul li {
    margin-bottom: 8px !important;
}
.user-fav ul li a {
    color: #abb7c4 !important;
    padding: 8px 15px !important;
    display: block !important;
    border-radius: 3px !important;
    transition: all 0.3s ease !important;
    text-transform: uppercase !important;
    font-weight: 600 !important;
    letter-spacing: 0.5px !important;
    font-size: 13px !important;
}
.user-fav ul li.active a {
    color: #e9d736 !important;
    background: transparent !important;
}
.user-fav ul li a:hover {
    color: #e9d736 !important;
    background: transparent !important;
}
.topbar-filter.user {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-bottom: 30px;
}
.topbar-filter.user p {
    color: #fff;
    margin: 0;
    white-space: nowrap;
    margin-right: 20px;
}
.topbar-filter.user p span {
    color: #e9d736;
    font-weight: bold;
}
.topbar-filter.user label {
    color: #abb7c4;
    margin: 0;
    white-space: nowrap;
}
.topbar-filter.user select {
    background: #020d18;
    border: 1px solid #405266;
    color: #fff;
    padding: 8px 15px;
    border-radius: 5px;
}
.flex-wrap-movielist.grid-fav {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 10px;
    margin-bottom: 20px;
}
.movie-item-style-2.style-3 {
    position: relative;
    border-radius: 5px;
    overflow: hidden;
    background: #0b1a2a;
}
.movie-item-style-2.style-3 .poster-link {
    display: block;
    position: relative;
}
.movie-item-style-2.style-3 img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    display: block;
}
.movie-item-style-2.style-3 .mv-item-infor {
    padding-left: 5px;
    background: #0b1a2a;
}
.movie-item-style-2.style-3 .mv-item-infor h6 {
    margin: 0 0 5px 0;
}
.movie-item-style-2.style-3 .mv-item-infor h6 a {
    color: #fff;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.3s ease;
}
.movie-item-style-2.style-3 .mv-item-infor h6 a:hover {
    color: #e9d736;
}
.movie-item-style-2.style-3 .mv-item-infor p {
    color: #e9d736;
    font-size: 13px;
    margin: 0;
}
.like-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(235, 110, 172, 0.95);
    color: #fff;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    z-index: 2;
}
.topbar-filter {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #405266;
}
.topbar-filter label {
    color: #abb7c4;
    margin: 0;
    white-space: nowrap;
}
.topbar-filter select {
    background: #020d18;
    border: 1px solid #405266;
    color: #fff;
    padding: 8px 15px;
    border-radius: 5px;
}
.pagination2 {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 5px;
}
.pagination2 span {
    color: #abb7c4;
    margin-right: 10px;
}
.pagination2 a {
    display: inline-block;
    padding: 5px 12px;
    background: #020d18;
    color: #fff;
    text-decoration: none;
    border-radius: 3px;
    transition: all 0.3s ease;
}
.pagination2 a.active,
.pagination2 a:hover {
    background: #eb70ac;
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

<div class="hero user-hero">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="hero-ct">
					<h1>{{ Auth::user()->name }}'s Profile</h1>
					<ul class="breadcumb">
						<li class="active"><a href="{{ route('home') }}">Home</a></li>
						<li> <span class="ion-ios-arrow-right"></span>My Movies</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="page-single">
	<div class="container">
		<div class="row ipad-width2">
			<div class="col-md-3 col-sm-12 col-xs-12">
				@include('profile.partials.sidebar')
			</div>
			<div class="col-md-9 col-sm-12 col-xs-12">
				<div class="topbar-filter user">
					<p>Found <span>{{ $movies->count() }} movies</span> in total</p>
					<label>Sort by:</label>
					<select onchange="sortMovies(this.value)">
						<option value="newest">Newest First</option>
						<option value="oldest">Oldest First</option>
						<option value="rating_high">Highest Rating</option>
						<option value="rating_low">Lowest Rating</option>
					</select>
				</div>

				@if($movies->count() > 0)
					<div class="flex-wrap-movielist grid-fav">
						@foreach($movies as $movie)
							@php
								$isLiked = App\Models\UserMovieLike::where('user_id', Auth::id())
									->where('movie_id', $movie->movie_id)
									->exists();
							@endphp
							<div class="movie-item-style-2 movie-item-style-1 style-3">
								@if($isLiked)
									<div class="like-badge">
										<i class="ion-heart"></i>
									</div>
								@endif
								<a href="{{ route('movies.show', $movie->movie_id) }}" class="poster-link">
									<img src="{{ $movie->movie_poster ?: asset('images/uploads/mv1.jpg') }}" alt="{{ $movie->movie_title }}">
								</a>
								<div class="mv-item-infor">
									<h6>
										<a href="{{ route('movies.show', $movie->movie_id) }}">
											{{ $movie->movie_title }}
											@if($movie->release_year)
												<span>({{ $movie->release_year }})</span>
											@endif
										</a>
									</h6>
									<p class="rate"><i class="ion-android-star"></i><span>{{ $movie->rating }}</span> /10</p>
								</div>
							</div>
						@endforeach
					</div>

					<div class="topbar-filter">
						<label>Movies per page:</label>
						<select>
							<option value="20">20 Movies</option>
							<option value="30">30 Movies</option>
							<option value="50">50 Movies</option>
						</select>
						
						<div class="pagination2">
							<span>Page 1 of 1:</span>
							<a class="active" href="#">1</a>
							<a href="#"><i class="ion-arrow-right-b"></i></a>
						</div>
					</div>
				@else
					<div style="text-align: center; padding: 100px 20px; color: #fff;">
						<i class="ion-ios-film-outline" style="font-size: 80px; color: #405266; margin-bottom: 20px;"></i>
						<h2 style="color: #e9d736; margin-bottom: 15px;">No Movies Yet</h2>
						<p style="color: #abb7c4; margin-bottom: 30px;">Start building your movie collection by rating and adding movies!</p>
						<a href="{{ route('movies.index') }}" class="redbtn" style="display: inline-block; padding: 12px 30px; background: #eb70ac; color: #fff; text-decoration: none; border-radius: 5px;">Browse Movies</a>
					</div>
				@endif
			</div>
		</div>
	</div>
</div>

@push('scripts')
<script>
function sortMovies(sortBy) {
    // Add sorting functionality here
    console.log('Sort by:', sortBy);
}
</script>
@endpush
@endsection
