@extends('layouts.app')

@section('title', $user->name . ' - Community Profile')

@push('styles')
<style>
/* Ensure body and html have dark background */
html, body {
    background-color: #020d18 !important;
    min-height: 100%;
    margin: 0;
    padding: 0;
}

.ht-header {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

/* Hero Section */
.hero.mv-single-hero {
    background: linear-gradient(135deg, #020d18 0%, #0d1b2a 100%);
    padding: 40px 0;
}

.page-single.movie-single {
    background: #020d18 !important;
    min-height: 100vh;
    padding: 50px 0 100px;
    position: relative;
}

.page-single.movie-single::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    opacity: 0.05;
    z-index: 0;
}

.page-single.movie-single .container {
    position: relative;
    z-index: 1;
    background: transparent;
}

.movie-single-ct {
    color: #fff;
}

.movie-single-ct h1.bd-hd {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 20px;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 20px;
}

.movie-single-ct h1.bd-hd .follow-btn {
    font-size: 14px;
    font-weight: 600;
    background: #eb70ac;
    color: #fff;
    border: none;
    padding: 8px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.movie-single-ct h1.bd-hd .follow-btn:hover {
    background: #d55a98;
    transform: translateY(-2px);
}

.movie-single-ct h1.bd-hd .follow-btn.following {
    background: #405266;
}

.movie-single-ct h1.bd-hd .follow-btn.following:hover {
    background: #eb70ac;
}

.movie-img {
    text-align: center;
    margin-bottom: 20px;
}

.page-single.movie-single .movie-img img {
    width: 150px !important;
    height: 150px !important;
    min-height: 150px !important;
    max-height: 150px !important;
    max-width: 150px !important;
    object-fit: cover !important;
    border-radius: 50% !important;
    box-shadow: 0 8px 20px rgba(0,0,0,0.4) !important;
    border: 3px solid #e9d736 !important;
    display: block !important;
    margin: 0 auto !important;
}

.sticky-sb {
    position: sticky;
    top: 20px;
}

.user-stats-box {
    padding: 20px;
    border-radius: 8px;
    margin-top: 25px;
    border: 1px solid #405266;
    width: 100%;
}

.user-stats-box h6 {
    color: #3e9fd8;
    font-size: 13px;
    margin-bottom: 15px;
    text-transform: uppercase;
    font-weight: bold;
    text-align: center;
    letter-spacing: 1px;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid rgba(64, 82, 102, 0.3);
    color: #abb7c4;
    font-size: 14px;
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-item span:first-child {
    color: #fff;
}

.stat-item span:last-child {
    color: #e9d736;
    font-weight: bold;
}

/* Movie Tabs */
.movie-tabs {
    margin-top: 40px;
}

.tabs .tab-links {
    list-style: none;
    padding: 0;
    margin: 0 0 30px 0;
    border-bottom: none;
    display: flex;
    gap: 0;
}

.tabs .tab-links li {
    background: transparent;
}

.tabs .tab-links li.active {
    background: transparent;
}

.tabs .tab-links li a {
    display: block;
    padding: 10px 20px;
    color: #fff;
    text-decoration: none;
    font-weight: bold;
    font-size: 12px;
    transition: all 0.3s ease;
    text-transform: uppercase;
}

.tabs .tab-links li.active a {
    color: #e9d736;
}

.tabs .tab-links li a:hover {
    color: #e9d736;
    text-decoration: none;
    background: transparent;
}

.tab-content .tab {
    display: none;
}

.tab-content .tab.active {
    display: block;
}

/* Movies Grid */
.movies-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.movie-item-card {
    background: rgba(35, 58, 80, 0.3);
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid #405266;
}

.movie-item-card:hover {
    transform: translateY(-5px);
    border-color: #e9d736;
    box-shadow: 0 5px 20px rgba(233, 215, 54, 0.2);
}

.movie-poster-container {
    position: relative;
    width: 100%;
    padding-bottom: 150%;
    overflow: hidden;
}

.movie-poster-container img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.rating-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0, 0, 0, 0.8);
    color: #e9d736;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 12px;
    font-weight: bold;
}

.movie-details {
    padding: 15px;
}

.movie-title {
    margin: 0 0 8px 0;
    font-size: 14px;
    line-height: 1.3;
}

.movie-title a {
    color: #fff;
    text-decoration: none;
}

.movie-title a:hover {
    color: #e9d736;
}

.movie-date {
    margin: 0;
    color: #abb7c4;
    font-size: 12px;
}

/* Activity List */
.activity-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.activity-card {
    background: rgba(35, 58, 80, 0.3);
    padding: 20px;
    border-radius: 8px;
    display: flex;
    gap: 20px;
    align-items: flex-start;
    border: 1px solid #405266;
    transition: all 0.3s ease;
}

.activity-card:hover {
    border-color: #3e9fd8;
    background: rgba(35, 58, 80, 0.5);
}

.activity-icon-wrapper {
    width: 50px;
    height: 50px;
    background: #3e9fd8;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.activity-icon-wrapper i {
    font-size: 24px;
    color: #fff;
}

.activity-info {
    flex: 1;
}

.activity-text {
    color: #fff;
    margin-bottom: 8px;
}

.activity-text strong {
    display: block;
    margin-bottom: 5px;
    color: #e9d736;
}

.activity-movie {
    color: #abb7c4;
    font-style: italic;
}

.activity-meta {
    display: flex;
    gap: 20px;
    font-size: 12px;
    color: #abb7c4;
}

.activity-points {
    color: #e9d736;
}

/* Reviews */
.review-item {
    background: rgba(35, 58, 80, 0.3);
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 1px solid #405266;
}

.review-header {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
}

.review-poster-wrapper {
    flex-shrink: 0;
}

.review-poster-img {
    width: 80px;
    height: 120px;
    object-fit: cover;
    border-radius: 5px;
}

.review-movie-title {
    margin: 0 0 10px 0;
    font-size: 18px;
}

.review-movie-title a {
    color: #fff;
    text-decoration: none;
}

.review-movie-title a:hover {
    color: #e9d736;
}

.review-stars {
    display: flex;
    align-items: center;
    gap: 5px;
}

.rating-value {
    color: #e9d736;
    font-weight: bold;
    margin-left: 10px;
}

.review-text {
    color: #abb7c4;
    line-height: 1.6;
    margin-bottom: 15px;
}

.review-footer {
    display: flex;
    gap: 20px;
    font-size: 12px;
    color: #abb7c4;
    padding-top: 15px;
    border-top: 1px solid rgba(64, 82, 102, 0.3);
}

/* No Content Message */
.no-content-message {
    text-align: center;
    padding: 60px 20px;
    color: #abb7c4;
}

.no-content-message i {
    font-size: 64px;
    color: #405266;
    margin-bottom: 20px;
    display: block;
}

.no-content-message p {
    color: #abb7c4;
    font-size: 16px;
}

@media (max-width: 768px) {
    .movie-single-ct h1.bd-hd {
        font-size: 1.8rem;
        flex-direction: column;
        align-items: flex-start;
    }
    
    .movies-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 15px;
    }
    
    .tabs .tab-links {
        flex-wrap: wrap;
    }
    
    .tabs .tab-links li a {
        padding: 8px 12px;
        font-size: 11px;
    }
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
                            <!-- <ul class="sub-menu">
                                <li><a href="{{ route('movies.index', ['category' => 'popular']) }}">Popular</a></li>
                                <li><a href="{{ route('movies.index', ['category' => 'top-rated']) }}">Top Rated</a></li>
                                <li><a href="{{ route('movies.index', ['category' => 'trending']) }}">Trending</a></li>
                                <li><a href="{{ route('movies.index', ['category' => 'upcoming']) }}">Upcoming</a></li>
                            </ul> -->
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
                        <li class="first active">
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

<div class="hero mv-single-hero">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<!-- Simple breadcrumb -->
			</div>
		</div>
	</div>
</div>

<div class="page-single movie-single">
	<div class="container">
		<div class="row ipad-width2">
			<div class="col-md-4 col-sm-12 col-xs-12">
				<div class="movie-img sticky-sb">
					<img src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
					
					<div class="user-stats-box">
						<h6>Statistics</h6>
						<div class="stat-item">
							<span>Followers</span>
							<span id="followersCount">{{ $stats['followers_count'] }}</span>
						</div>
						<div class="stat-item">
							<span>Following</span>
							<span>{{ $stats['following_count'] }}</span>
						</div>
						<div class="stat-item">
							<span>Movies Watched</span>
							<span>{{ $stats['movies_watched'] }}</span>
						</div>
						<div class="stat-item">
							<span>Reviews</span>
							<span>{{ $stats['reviews_count'] }}</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-8 col-sm-12 col-xs-12">
				<div class="movie-single-ct main-content">
					<h1 class="bd-hd">
						{{ $user->name }} 
						<span style="color: #abb7c4; font-size: 18px; font-weight: normal;">@{{ $user->username }}</span>
						@auth
							@if(Auth::id() !== $user->id)
								<button class="follow-btn" id="followBtn" data-user-id="{{ $user->id }}" onclick="toggleFollow(event)">
									Follow
								</button>
							@endif
						@else
							<button class="follow-btn" onclick="alert('Please login to follow users'); return false;">
								Follow
							</button>
						@endauth
					</h1>
					
					@if($user->bio)
						<p style="color: #abb7c4; margin-bottom: 30px; line-height: 1.8; font-size: 15px;">{{ $user->bio }}</p>
					@endif
					
					<div class="movie-tabs">
						<div class="tabs">
							<ul class="tab-links tabs-mv">
								<li class="active"><a href="#favorites">Favorites</a></li>
								<li><a href="#activity">Recent Activity</a></li>
								<li><a href="#movies">Movies</a></li>
								<li><a href="#reviews">Reviews</a></li>                     
							</ul>
						    <div class="tab-content">
						        <div id="favorites" class="tab active">
						            @if($favoriteMovies->count() > 0)
										<div class="movies-grid">
											@foreach($favoriteMovies as $movie)
												<div class="movie-item-card">
													<a href="{{ route('movies.show', $movie->movie_id) }}" class="movie-poster-link">
														<div class="movie-poster-container">
															@if($movie->poster_url)
																<img src="{{ $movie->poster_url }}" 
																     alt="{{ $movie->movie_title }}" 
																     loading="lazy"
																     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
																<div class="movie-poster-placeholder" style="display: none; width: 100%; height: 100%; background: #405266; align-items: center; justify-content: center; color: #e9d736; font-size: 32px; font-weight: bold;">
																	{{ substr($movie->movie_title, 0, 2) }}
																</div>
															@else
																<div class="movie-poster-placeholder" style="width: 100%; height: 100%; background: #405266; display: flex; align-items: center; justify-content: center; color: #e9d736; font-size: 32px; font-weight: bold;">
																	{{ substr($movie->movie_title, 0, 2) }}
																</div>
															@endif
															
															@if($movie->user_rating)
																<div class="rating-badge">
																	<i class="ion-star"></i> {{ number_format($movie->user_rating, 1) }}
																</div>
															@endif
														</div>
													</a>
													
													<div class="movie-details">
														<h4 class="movie-title">
															<a href="{{ route('movies.show', $movie->movie_id) }}">{{ Str::limit($movie->movie_title, 30) }}</a>
														</h4>
														
														@if($movie->watched_at)
															<p class="movie-date">
																<i class="ion-calendar"></i> 
																{{ $movie->watched_at->format('M d, Y') }}
															</p>
														@endif
													</div>
												</div>
											@endforeach
										</div>
										
										@if($favoriteMovies->hasPages())
											<div class="pagination-container" style="text-align: center; margin-top: 30px;">
												{{ $favoriteMovies->appends(['movies_page' => request('movies_page'), 'reviews_page' => request('reviews_page')])->links() }}
											</div>
										@endif
									@else
										<div class="no-content-message">
											<i class="ion-ios-film-outline"></i>
											<p>{{ $user->name }} hasn't added any favorite movies yet.</p>
										</div>
									@endif
						        </div>
						        
						        <div id="activity" class="tab">
						            @if($recentActivities->count() > 0)
										<div class="activity-list">
											@foreach($recentActivities as $activity)
												<div class="activity-card">
													<div class="activity-icon-wrapper">
														@switch($activity->activity_type)
															@case('favorite')
																<i class="ion-heart"></i>
																@break
															@case('review')
																<i class="ion-compose"></i>
																@break
															@case('follow')
																<i class="ion-person-add"></i>
																@break
															@case('movie_watched')
																<i class="ion-play"></i>
																@break
															@case('login')
																<i class="ion-log-in"></i>
																@break
															@case('profile_update')
																<i class="ion-edit"></i>
																@break
															@default
																<i class="ion-ios-pulse"></i>
														@endswitch
													</div>
													
													<div class="activity-info">
														<div class="activity-text">
															@switch($activity->activity_type)
																@case('favorite')
																	<strong>Added a movie to favorites</strong>
																	@break
																@case('review')
																	<strong>Wrote a movie review</strong>
																	@break
																@case('follow')
																	<strong>Started following someone</strong>
																	@break
																@case('movie_watched')
																	<strong>Watched a movie</strong>
																	@break
																@case('login')
																	<strong>Logged in</strong>
																	@break
																@case('profile_update')
																	<strong>Updated profile</strong>
																	@break
																@default
																	<strong>{{ ucfirst(str_replace('_', ' ', $activity->activity_type)) }}</strong>
															@endswitch
															
															@if($activity->activity_data && isset($activity->activity_data['movie_title']))
																<span class="activity-movie">{{ $activity->activity_data['movie_title'] }}</span>
															@endif
														</div>
														
														<div class="activity-meta">
															<span class="activity-time">{{ $activity->created_at->diffForHumans() }}</span>
															<span class="activity-points"><i class="ion-trophy"></i> +{{ $activity->points }} pts</span>
														</div>
													</div>
												</div>
											@endforeach
										</div>
									@else
										<div class="no-content-message">
											<i class="ion-ios-pulse-outline"></i>
											<p>No recent activity to show.</p>
										</div>
									@endif
						        </div>
						        
						        <div id="movies" class="tab">
						            @if($userMovies->count() > 0)
										<div class="movies-grid">
											@foreach($userMovies as $movie)
												<div class="movie-item-card">
													<a href="{{ route('movies.show', $movie->movie_id) }}" class="movie-poster-link">
														<div class="movie-poster-container">
															@if($movie->poster_url)
																<img src="{{ $movie->poster_url }}" 
																     alt="{{ $movie->movie_title }}" 
																     loading="lazy"
																     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
																<div class="movie-poster-placeholder" style="display: none; width: 100%; height: 100%; background: #405266; align-items: center; justify-content: center; color: #e9d736; font-size: 32px; font-weight: bold;">
																	{{ substr($movie->movie_title, 0, 2) }}
																</div>
															@else
																<div class="movie-poster-placeholder" style="width: 100%; height: 100%; background: #405266; display: flex; align-items: center; justify-content: center; color: #e9d736; font-size: 32px; font-weight: bold;">
																	{{ substr($movie->movie_title, 0, 2) }}
																</div>
															@endif
															
															@if($movie->rating)
																<div class="rating-badge">
																	<i class="ion-star"></i> {{ number_format($movie->rating, 1) }}
																</div>
															@endif
														</div>
													</a>
													
													<div class="movie-details">
														<h4 class="movie-title">
															<a href="{{ route('movies.show', $movie->movie_id) }}">{{ Str::limit($movie->movie_title, 30) }}</a>
														</h4>
														
														@if($movie->created_at)
															<p class="movie-date">
																<i class="ion-calendar"></i> 
																{{ $movie->created_at->format('M d, Y') }}
															</p>
														@endif
													</div>
												</div>
											@endforeach
										</div>
										
										@if($userMovies->hasPages())
											<div class="pagination-container" style="text-align: center; margin-top: 30px;">
												{{ $userMovies->appends(['favorites_page' => request('favorites_page'), 'reviews_page' => request('reviews_page')])->links() }}
											</div>
										@endif
									@else
										<div class="no-content-message">
											<i class="ion-ios-film-outline"></i>
											<p>{{ $user->name }} hasn't watched any movies yet.</p>
										</div>
									@endif
						        </div>
						        
						        <div id="reviews" class="tab">
									@if($userReviews->count() > 0)
										@foreach($userReviews as $review)
											<div class="review-item">
												<div class="review-header">
													<div class="review-poster-wrapper">
														@if($review->poster_url)
															<a href="{{ route('movies.show', $review->movie_id) }}">
																<img src="{{ $review->poster_url }}" 
																     alt="{{ $review->movie_title }}" 
																     class="review-poster-img"
																     onerror="this.style.display='none'; this.parentElement.nextElementSibling.style.display='flex';">
															</a>
															<div class="review-poster-placeholder" style="display: none; width: 80px; height: 120px; background: #405266; align-items: center; justify-content: center; color: #e9d736; font-size: 24px; font-weight: bold; border-radius: 5px;">
																{{ substr($review->movie_title, 0, 2) }}
															</div>
														@else
															<div class="review-poster-placeholder" style="width: 80px; height: 120px; background: #405266; display: flex; align-items: center; justify-content: center; color: #e9d736; font-size: 24px; font-weight: bold; border-radius: 5px;">
																{{ substr($review->movie_title, 0, 2) }}
															</div>
														@endif
													</div>
													
													<div class="review-movie-details">
														<h4 class="review-movie-title">
															<a href="{{ route('movies.show', $review->movie_id) }}">{{ $review->movie_title }}</a>
														</h4>
														@if($review->rating)
															<div class="review-stars">
																@for($i = 1; $i <= 10; $i++)
																	@if($i <= $review->rating)
																		<i class="ion-star" style="color: #e9d736;"></i>
																	@else
																		<i class="ion-star-outline" style="color: #405266;"></i>
																	@endif
																@endfor
																<span class="rating-value">{{ number_format($review->rating, 1) }}/10</span>
															</div>
														@endif
														@if($review->watched_before)
															<span class="watched-badge" style="display: inline-block; background: #405266; padding: 3px 8px; border-radius: 3px; font-size: 11px; margin-top: 5px;">
																<i class="ion-checkmark"></i> Watched Before
															</span>
														@endif
													</div>
												</div>
												
												<div class="review-body">
													<p class="review-text">{{ $review->review }}</p>
													
													<div class="review-footer">
														<span class="review-added">
															<i class="ion-clock"></i> Posted {{ $review->created_at->diffForHumans() }}
														</span>
													</div>
												</div>
											</div>
										@endforeach
										
										@if($userReviews->hasPages())
											<div class="pagination-container" style="text-align: center; margin-top: 30px;">
												{{ $userReviews->appends(['favorites_page' => request('favorites_page'), 'movies_page' => request('movies_page')])->links() }}
											</div>
										@endif
									@else
										<div class="no-content-message">
											<i class="ion-compose-outline"></i>
											<p>{{ $user->name }} hasn't written any movie reviews yet.</p>
										</div>
									@endif
						        </div>
						    </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Tab functionality
    $('.tabs .tab-links a').on('click', function(e) {
        e.preventDefault();
        var currentAttrValue = $(this).attr('href');
        
        $('.tab-content ' + currentAttrValue).show().siblings().hide();
        $(this).parent('li').addClass('active').siblings().removeClass('active');
    });
    
    // Load follow status on page load
    @auth
        @if(Auth::id() !== $user->id)
            checkFollowStatus();
        @endif
    @endauth
});

// Check if current user is following this profile user
function checkFollowStatus() {
    const userId = $('#followBtn').data('user-id');
    
    fetch(`/community/follow-status/${userId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.is_following) {
            $('#followBtn').text('Following').addClass('following');
        }
    })
    .catch(error => console.error('Error checking follow status:', error));
}

// Toggle follow
function toggleFollow(event) {
    event.preventDefault();
    
    const button = $('#followBtn');
    const userId = button.data('user-id');
    const isFollowing = button.hasClass('following');
    const action = isFollowing ? 'unfollow' : 'follow';
    
    if (isFollowing && !confirm('Are you sure you want to unfollow {{ $user->name }}?')) {
        return;
    }
    
    button.prop('disabled', true);
    const originalText = button.text();
    button.text(isFollowing ? 'Unfollowing...' : 'Following...');
    
    fetch(`/profile/${action}/${userId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (action === 'follow') {
                button.text('Following').addClass('following');
                // Update followers count
                const currentCount = parseInt($('#followersCount').text());
                $('#followersCount').text(currentCount + 1);
            } else {
                button.text('Follow').removeClass('following');
                // Update followers count
                const currentCount = parseInt($('#followersCount').text());
                $('#followersCount').text(currentCount - 1);
            }
            button.prop('disabled', false);
        } else {
            button.prop('disabled', false);
            button.text(originalText);
            alert(data.message || 'An error occurred. Please try again.');
        }
    })
    .catch(error => {
        button.prop('disabled', false);
        button.text(originalText);
        console.error('Error:', error);
        alert('Failed to ' + action + ' user. Please try again.');
    });
}
</script>
@endpush