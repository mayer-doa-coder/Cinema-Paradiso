@extends('layouts.app')

@section('title', 'My Movies - Cinema Paradiso')

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
.user-fav ul li a {
    color: #abb7c4;
    text-decoration: none;
    display: block;
    padding: 8px 15px;
    border-radius: 3px;
    transition: all 0.3s ease;
}
.user-fav ul li a:hover {
    background: #020d18;
    color: #dcf836;
}
.user-fav ul li.active a {
    background: #020d18;
    color: #dcf836;
    border-left: 3px solid #eb70ac;
}
.topbar-filter.user {
    margin-bottom: 30px;
}
.topbar-filter.user p {
    color: #fff;
}
.topbar-filter.user p span {
    color: #dcf836;
    font-weight: bold;
}
.topbar-filter.user label {
    color: #abb7c4;
    margin-right: 10px;
}
.topbar-filter.user select {
    background: #0b1a2a;
    border: 1px solid #405266;
    color: #fff;
    padding: 8px 15px;
    border-radius: 5px;
}
.topbar-filter.user .list,
.topbar-filter.user .grid {
    display: inline-block;
    padding: 8px 12px;
    background: #0b1a2a;
    color: #abb7c4;
    border-radius: 5px;
    margin-left: 10px;
    text-decoration: none;
    transition: all 0.3s ease;
}
.topbar-filter.user .grid.active,
.topbar-filter.user .list:hover,
.topbar-filter.user .grid:hover {
    background: #eb70ac;
    color: #fff;
}
.flex-wrap-movielist.grid-fav {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}
.movie-item-style-2.style-3 {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease;
    background: #0b1a2a;
}
.movie-item-style-2.style-3:hover {
    transform: translateY(-5px);
}
.movie-item-style-2.style-3 img {
    width: 100%;
    height: 270px;
    object-fit: cover;
}
.movie-item-style-2.style-3 .hvr-inner {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.movie-item-style-2.style-3:hover .hvr-inner {
    opacity: 1;
}
.movie-item-style-2.style-3 .hvr-inner a {
    color: #fff;
    font-size: 14px;
    text-decoration: none;
    padding: 10px 20px;
    background: #eb70ac;
    border-radius: 5px;
    transition: all 0.3s ease;
}
.movie-item-style-2.style-3 .hvr-inner a:hover {
    background: #d55a92;
}
.movie-item-style-2.style-3 .mv-item-infor {
    padding: 15px;
    background: #0b1a2a;
}
.movie-item-style-2.style-3 .mv-item-infor h6 {
    margin: 0 0 8px 0;
}
.movie-item-style-2.style-3 .mv-item-infor h6 a {
    color: #fff;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.3s ease;
}
.movie-item-style-2.style-3 .mv-item-infor h6 a:hover {
    color: #dcf836;
}
.movie-item-style-2.style-3 .mv-item-infor p {
    color: #dcf836;
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
    gap: 15px;
    padding: 20px;
    background: #0b1a2a;
    border-radius: 5px;
    margin-bottom: 20px;
}
.pagination2 {
    margin-left: auto;
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
    margin: 0 3px;
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
							<li><a href="{{ route('user.reviews') }}">Reviews</a></li>
							<li class="active"><a href="{{ route('user.movies') }}">Movies</a></li>
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
				<div class="topbar-filter user">
					<p>Found <span>{{ $movies->count() }} movies</span> in total</p>
					<label>Sort by:</label>
					<select onchange="sortMovies(this.value)">
						<option value="newest">Newest First</option>
						<option value="oldest">Oldest First</option>
						<option value="rating_high">Highest Rating</option>
						<option value="rating_low">Lowest Rating</option>
					</select>
					<a href="#" class="list"><i class="ion-ios-list-outline"></i></a>
					<a href="#" class="grid active"><i class="ion-grid"></i></a>
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
								<img src="{{ $movie->movie_poster ?: asset('images/uploads/mv1.jpg') }}" alt="{{ $movie->movie_title }}">
								<div class="hvr-inner">
									<a href="{{ route('movies.show', $movie->movie_id) }}">View Details</a>
								</div>
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
						<h2 style="color: #dcf836; margin-bottom: 15px;">No Movies Yet</h2>
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
