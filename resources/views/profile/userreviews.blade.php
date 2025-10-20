@extends('layouts.app')

@section('title', 'My Reviews - Cinema Paradiso')

@push('styles')
<style>
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
    background: #0b1a2a;
    padding: 20px;
    border-radius: 5px;
    border: 1px solid #405266;
}
.user-img {
    text-align: center;
    margin-bottom: 30px;
}
.user-img img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    margin-bottom: 15px;
    object-fit: cover;
    border: 3px solid #dcf836;
}
.user-img .redbtn {
    display: inline-block;
    padding: 10px 20px;
    background: #eb70ac;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    transition: all 0.3s ease;
}
.user-img .redbtn:hover {
    background: #d55a92;
}
.user-fav {
    margin-bottom: 25px;
}
.user-fav p {
    color: #dcf836;
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 15px;
    text-transform: uppercase;
}
.user-fav ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.user-fav ul li {
    margin-bottom: 10px;
}
.user-fav ul li a,
.user-fav ul li button {
    color: #abb7c4;
    text-decoration: none;
    display: block;
    padding: 8px 15px;
    border-radius: 3px;
    transition: all 0.3s ease;
    width: 100%;
    text-align: left;
}
.user-fav ul li a:hover,
.user-fav ul li button:hover {
    background: #020d18;
    color: #dcf836;
}
.user-fav ul li.active a {
    background: #020d18;
    color: #dcf836;
    border-left: 3px solid #eb70ac;
}
.topbar-filter {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: #0b1a2a;
    border-radius: 5px;
    margin-bottom: 30px;
}
.topbar-filter p {
    color: #fff;
    margin: 0;
}
.topbar-filter p span {
    color: #dcf836;
    font-weight: bold;
}
.topbar-filter label {
    color: #abb7c4;
    margin: 0 10px 0 0;
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
    border-color: #dcf836;
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
    color: #dcf836;
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
    color: #dcf836;
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
</style>
@endpush

@section('content')
<div class="hero user-hero">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="hero-ct">
					<h1>{{ Auth::user()->name }}'s profile</h1>
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
				<div class="user-information">
					<div class="user-img">
						<a href="{{ route('user.profile') }}">
							<img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/uploads/user-img.png') }}" alt="User Avatar">
						</a>
						<a href="{{ route('user.profile') }}" class="redbtn">Change avatar</a>
					</div>
					<div class="user-fav">
						<p>Account Details</p>
						<ul>
							<li><a href="{{ route('user.profile') }}">Profile</a></li>
							<li><a href="{{ route('user.watchlist') }}">Watchlist</a></li>
							<li class="active"><a href="{{ route('user.reviews') }}">Reviews</a></li>
							<li><a href="{{ route('user.movies') }}">Movies</a></li>
							<li><a href="{{ route('user.list') }}">List</a></li>
						</ul>
					</div>
					<div class="user-fav">
						<p>Others</p>
						<ul>
							<li>
								<form action="{{ route('auth.logout') }}" method="POST" style="display: inline;">
									@csrf
									<button type="submit" style="background: none; border: none; color: #abb7c4; cursor: pointer; padding: 8px 15px; display: block; width: 100%; text-align: left; border-radius: 3px; transition: all 0.3s ease;">
										Log out
									</button>
								</form>
							</li>
						</ul>
					</div>
				</div>
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
						<h2 style="color: #dcf836; margin-bottom: 15px;">No Reviews Yet</h2>
						<p style="color: #abb7c4; margin-bottom: 30px;">Start reviewing movies you've watched!</p>
						<a href="{{ route('movies.index') }}" class="redbtn" style="display: inline-block; padding: 12px 30px; background: #eb70ac; color: #fff; text-decoration: none; border-radius: 5px;">Browse Movies</a>
					</div>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection
