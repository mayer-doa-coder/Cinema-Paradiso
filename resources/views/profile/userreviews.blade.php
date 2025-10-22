@extends('layouts.app')

@section('title', 'My Reviews - Cinema Paradiso')

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
    color: #dcf836 !important;
    background: transparent !important;
}
.user-fav ul li a:hover {
    color: #dcf836 !important;
    background: transparent !important;
}
.topbar-filter {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 1px solid #405266;
}
.topbar-filter p {
    color: #fff;
    margin: 0;
    white-space: nowrap;
    margin-right: 20px;
}
.topbar-filter p span {
    color: #e9d736;
    font-weight: bold;
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
.movie-item-style-2.userrate {
    display: flex;
    background: #0b1a2a;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 25px;
    border: 1px solid #405266;
    transition: all 0.3s ease;
}
.movie-item-style-2.userrate:hover {
    border-color: #e9d736;
}
.movie-item-style-2.userrate.last {
    margin-bottom: 30px;
}
.movie-item-style-2.userrate img {
    width: 150px;
    height: 225px;
    object-fit: cover;
    border-radius: 8px;
    margin-right: 25px;
    flex-shrink: 0;
}
.movie-item-style-2.userrate .mv-item-infor {
    flex-grow: 1;
}
.movie-item-style-2.userrate .mv-item-infor h6 {
    color: #fff;
    font-size: 20px;
    margin-bottom: 15px;
}
.movie-item-style-2.userrate .mv-item-infor h6 a {
    color: #fff;
    text-decoration: none;
    transition: color 0.3s ease;
}
.movie-item-style-2.userrate .mv-item-infor h6 a:hover {
    color: #e9d736;
}
.movie-item-style-2.userrate .mv-item-infor h6 span {
    color: #abb7c4;
    font-size: 16px;
}
.movie-item-style-2.userrate .mv-item-infor p.time.sm-text {
    color: #abb7c4;
    font-size: 13px;
    margin: 10px 0 5px 0;
    text-transform: uppercase;
}
.movie-item-style-2.userrate .mv-item-infor p.rate {
    color: #e9d736;
    font-size: 18px;
    margin-bottom: 15px;
}
.movie-item-style-2.userrate .mv-item-infor p.rate i {
    margin-right: 5px;
}
.movie-item-style-2.userrate .mv-item-infor p.rate span {
    font-weight: bold;
}
.movie-item-style-2.userrate .mv-item-infor p.time.sm {
    color: #abb7c4;
    font-size: 13px;
    margin: 10px 0 15px 0;
}
.movie-item-style-2.userrate .mv-item-infor p {
    color: #abb7c4;
    line-height: 1.8;
    font-size: 14px;
}
.review-title {
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    margin: 15px 0 10px 0;
}
.watched-badge {
    display: inline-block;
    padding: 5px 12px;
    background: #eb70ac;
    color: #fff;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    margin-left: 10px;
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
.topbar-filter.bottom {
    margin-top: 30px;
    margin-bottom: 0;
    padding-top: 15px;
    padding-bottom: 0;
    border-top: 1px solid #405266;
    border-bottom: none;
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

<div class="hero user-hero">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="hero-ct">
					<h1>{{ Auth::user()->name }}'s Profile</h1>
					<ul class="breadcumb">
						<li class="active"><a href="{{ route('home') }}">Home</a></li>
						<li> <span class="ion-ios-arrow-right"></span>My Reviews</li>
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
				<div class="topbar-filter">
					<p>Found <span>{{ $reviews->count() }} reviews</span> in total</p>
					<label>Sort by:</label>
					<select>
						<option value="newest">Newest First</option>
						<option value="oldest">Oldest First</option>
						<option value="rating_high">Highest Rating</option>
						<option value="rating_low">Lowest Rating</option>
					</select>
				</div>

				@if($reviews->count() > 0)
					@foreach($reviews as $review)
						<div class="movie-item-style-2 userrate {{ $loop->last ? 'last' : '' }}">
							<img src="{{ $review->movie_poster ?: asset('images/uploads/mv1.jpg') }}" alt="{{ $review->movie_title }}">
							<div class="mv-item-infor">
								<h6>
									<a href="{{ route('movies.show', $review->movie_id) }}">
										{{ $review->movie_title }} 
										@if($review->release_year)
											<span>({{ $review->release_year }})</span>
										@endif
									</a>
									@if($review->watched_before)
										<span class="watched-badge">Watched Before</span>
									@endif
								</h6>
								<p class="time sm-text">Your rating:</p>
								<p class="rate"><i class="ion-android-star"></i><span>{{ $review->rating }}</span> /10</p>
								<p class="time sm-text">Your review:</p>
								<p class="time sm">{{ $review->created_at->format('d F Y') }}</p>
								<p>{{ $review->review }}</p>
							</div>
						</div>
					@endforeach

					<div class="topbar-filter">
						<label>Reviews per page:</label>
						<select>
							<option value="20">20 Reviews</option>
							<option value="10">10 Reviews</option>
						</select>
						<div class="pagination2">
							<span>Page 1 of 1:</span>
							<a class="active" href="#">1</a>
							<a href="#"><i class="ion-arrow-right-b"></i></a>
						</div>
					</div>
				@else
					<div style="text-align: center; padding: 100px 20px; color: #fff;">
						<i class="ion-compose" style="font-size: 80px; color: #405266; margin-bottom: 20px;"></i>
						<h2 style="color: #e9d736; margin-bottom: 15px;">No Reviews Yet</h2>
						<p style="color: #abb7c4; margin-bottom: 30px;">Start reviewing movies you've watched!</p>
						<a href="{{ route('movies.index') }}" class="redbtn" style="display: inline-block; padding: 12px 30px; background: #eb70ac; color: #fff; text-decoration: none; border-radius: 5px;">Browse Movies</a>
					</div>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection
