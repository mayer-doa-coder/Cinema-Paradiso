@extends('layouts.app')

@section('title', 'Cinema Paradiso - Celebrity Grid')

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
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									{{ Auth::user()->name }} <i class="fa fa-angle-down" aria-hidden="true"></i>
								</a>
								<ul class="dropdown-menu">
									<li><a href="#">Profile</a></li>
									<li><a href="#">Settings</a></li>
									<li><a href="#">Movies</a></li>
									<li><a href="#">Reviews</a></li>
									<li><a href="#">Watchlist</a></li>
									<li><a href="#" onclick="logout()">Logout</a></li>
								</ul>
							</li>
						@else
							<li class="loginLink"><a href="#">LOG In</a></li>
							<li class="btn signupLink"><a href="#">sign up</a></li>
						@endauth
					</ul>
				</div>
	    </nav>
	    
	    <!-- top search form -->
	    <div class="top-search">
	    	<div class="search-dropdown">
	    		<i class="ion-ios-list-outline"></i>
		    	<select id="search-type">
					<option value="movies">Movies</option>
					<option value="tvshows">TV Shows</option>
				</select>
			</div>
			<div class="search-input">
				<input type="text" id="search-query" placeholder="Search for a movie, TV Show that you are looking for">
				<i class="ion-ios-search" id="search-icon" style="cursor: pointer;"></i>
			</div>
	    </div>
	</div>
</header>
<!-- END | Header -->

<div class="hero common-hero">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="hero-ct">
					<h1>celebrity listing - grid</h1>
					<ul class="breadcumb">
						<li class="active"><a href="{{ route('home') }}">Home</a></li>
						<li> <span class="ion-ios-arrow-right"></span> celebrity listing</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- celebrity grid v1 section-->
<div class="page-single">
	<div class="container">
		<div class="row ipad-width2">
			<div class="col-md-9 col-sm-12 col-xs-12">
				<div class="topbar-filter">
					<p>Found <span>1,608 celebrities</span> in total</p>
					<label>Sort by:</label>
					<select>
						<option value="popularity">Popularity Descending</option>
						<option value="popularity">Popularity Ascending</option>
						<option value="rating">Rating Descending</option>
						<option value="rating">Rating Ascending</option>
						<option value="date">Release date Descending</option>
						<option value="date">Release date Ascending</option>
					</select>
					<a href="celebritylist.html" class="list"><i class="ion-ios-list-outline "></i></a>
					<a  href="celebritygrid01.html" class="grid"><i class="ion-grid active"></i></a>
				</div>
				<div class="celebrity-items">
					<div class="ceb-item">
						<a href="celebritysingle.html"><img src="images/uploads/ceb1.jpg" alt=""></a>
						<div class="ceb-infor">
							<h2><a href="celebritylist.html">Tom Hardy</a></h2>
							<span>actor, usa</span>
						</div>
					</div>
					<div class="ceb-item">
						<a href="celebritylist.html"><img src="images/uploads/ceb2.jpg" alt=""></a>						
						<div class="ceb-infor">
							<h2><a href="celebritylist.html">Leonardo DiCaprio</a></h2>
							<span>actor, mexico</span>
						</div>
					</div>
					<div class="ceb-item">
						<a href="celebritysingle.html"><img src="images/uploads/ceb3.jpg" alt=""></a>
						<div class="ceb-infor">
							<h2><a href="celebritylist.html">Emma Stone</a></h2>
							<span>Actress, usa</span>
						</div>
					</div>
					<div class="ceb-item">
						<a href="celebritysingle.html"> <img src="images/uploads/ceb4.jpg" alt=""></a>
						
						<div class="ceb-infor">
							<h2><a href="celebritylist.html">Olga Kurylenko</a></h2>
							<span>Actress, sweden</span>
						</div>
					</div>
					<div class="ceb-item">
						<a href="celebritysingle.html"><img src="images/uploads/ceb5.jpg" alt=""></a>
						<div class="ceb-infor">
							<h2><a href="celebritylist.html">Micheal Bay</a></h2>
							<span>Director, france</span>
						</div>
					</div>
					<div class="ceb-item">
						<a href="celebritysingle.html"><img src="images/uploads/ceb6.jpg" alt=""></a>
						<div class="ceb-infor">
							<h2><a href="celebritylist.html">Martin Scorsese</a></h2>
							<span>Director, italy</span>
						</div>
					</div>
					<div class="ceb-item">
						<a href="celebritysingle.html"><img src="images/uploads/ceb7.jpg" alt=""></a>
						<div class="ceb-infor">
							<h2><a href="celebritylist.html">TJohnson Staham</a></h2>
							<span>actor, uk</span>
						</div>
					</div>
					<div class="ceb-item">
						<a href="celebritysingle.html"><img src="images/uploads/ceb8.jpg" alt=""></a>
						<div class="ceb-infor">
							<h2><a href="celebritylist.html">Haley Bennett</a></h2>
							<span>actress, france</span>
						</div>
					</div>
					<div class="ceb-item">
						<a href="celebritysingle.html"><img src="images/uploads/ceb9.jpg" alt=""></a>
						<div class="ceb-infor">
							<h2><a href="celebritylist.html">Teresa Palmer</a></h2>
							<span>actress, uk</span>
						</div>
					</div>
				</div>
				<div class="topbar-filter">
					<label>Reviews per page:</label>
					<select>
						<option value="range">9 Reviews</option>
						<option value="saab">10 Reviews</option>
					</select>
					
					<div class="pagination2">
						<span>Page 1 of 6:</span>
						<a class="active" href="#">1</a>
						<a href="#">2</a>
						<a href="#">3</a>
						<a href="#">4</a>
						<a href="#">5</a>
						<a href="#">6</a>
						<a href="#"><i class="ion-arrow-right-b"></i></a>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-xs-12 col-sm-12">
				<div class="sidebar">
						<div class="searh-form">
						<h4 class="sb-title">Search celebrity</h4>
						<form class="form-style-1 celebrity-form" action="#">
							<div class="row">
								<div class="col-md-12 form-it">
									<label>Celebrity name</label>
									<input type="text" placeholder="Enter keywords">
								</div>
								<div class="col-md-12 form-it">
									<label>Celebrity Letter</label>
									<select>
									  <option value="range">A</option>
									  <option value="saab">B</option>
									</select>
								</div>
								<div class="col-md-12 form-it">
									<label>Category</label>
									<select>
									  <option value="range">Actress</option>
									  <option value="saab">Others</option>
									</select>
								</div>
								<div class="col-md-12 form-it">
									<label>Year of birth</label>
									<div class="row">
										<div class="col-md-6">
											<select>
											  <option value="range">1970</option>
											  <option value="number">Other</option>
											</select>
										</div>
										<div class="col-md-6">
											<select>
											  <option value="range">1990</option>
											  <option value="number">others</option>
											</select>
										</div>
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
							<div class="movie-wallpaper" style="position: relative; width: 336px; height: 296px; overflow: hidden; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.3);">
								<img src="{{ $randomWallpaper['backdrop_url'] }}" alt="{{ $randomWallpaper['title'] ?? 'Movie Wallpaper' }}" 
									 style="width: 100%; height: 100%; object-fit: cover;">
								<div class="wallpaper-overlay" style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.8)); color: white; padding: 15px;">
									<h5 style="margin: 0; font-size: 14px; font-weight: bold;">{{ $randomWallpaper['title'] ?? 'Featured Movie' }}</h5>
									@if(!empty($randomWallpaper['overview']))
										<p style="margin: 5px 0 0; font-size: 11px; opacity: 0.9;">{{ $randomWallpaper['overview'] }}</p>
									@endif
								</div>
							</div>
						@else
							<img src="{{ asset('images/uploads/ads1.png') }}" alt="">
						@endif
					</div>
					<div class="celebrities">
						<h4 class="sb-title">featured celebrity</h4>
						<div class="celeb-item">
							<a href="#"><img src="images/uploads/ava1.jpg" alt=""></a>
							<div class="celeb-author">
								<h6><a href="#">Samuel N. Jack</a></h6>
								<span>Actor</span>
							</div>
						</div>
						<div class="celeb-item">
							<a href="#"><img src="images/uploads/ava2.jpg" alt=""></a>
							<div class="celeb-author">
								<h6><a href="#">Benjamin Carroll</a></h6>
								<span>Actor</span>
							</div>
						</div>
						<div class="celeb-item">
							<a href="#"><img src="images/uploads/ava3.jpg" alt=""></a>
							<div class="celeb-author">
								<h6><a href="#">Beverly Griffin</a></h6>
								<span>Actor</span>
							</div>
						</div>
						<div class="celeb-item">
							<a href="#"><img src="images/uploads/ava4.jpg" alt=""></a>
							<div class="celeb-author">
								<h6><a href="#">Justin Weaver</a></h6>
								<span>Actor</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--end of celebrity grid v1 section-->
<!-- footer section-->
<footer class="ht-footer">
	<div class="container">
		<div class="flex-parent-ft">
			<div class="flex-child-ft item1">
				 <a href="index.html"><img class="logo" src="images/cinema_paradiso.png" alt=""></a>
				 <p>5th Avenue st, manhattan<br>
				New York, NY 10001</p>
				<p>Call us: <a href="#">(+01) 202 342 6789</a></p>
			</div>
			<div class="flex-child-ft item2">
				<h4>Resources</h4>
				<ul>
					<li><a href="#">About</a></li> 
					<li><a href="#">Blockbuster</a></li>
					<li><a href="#">Contact Us</a></li>
					<li><a href="#">Forums</a></li>
					<li><a href="#">Blog</a></li>
					<li><a href="#">Help Center</a></li>
				</ul>
			</div>
			<div class="flex-child-ft item3">
				<h4>Legal</h4>
				<ul>
					<li><a href="#">Terms of Use</a></li> 
					<li><a href="#">Privacy Policy</a></li>	
					<li><a href="#">Security</a></li>
				</ul>
			</div>
			<div class="flex-child-ft item4">
				<h4>Account</h4>
				<ul>
					<li><a href="#">My Account</a></li> 
					<li><a href="#">Watchlist</a></li>	
					<li><a href="#">Collections</a></li>
					<li><a href="#">User Guide</a></li>
				</ul>
			</div>
			<div class="flex-child-ft item5">
				<h4>Newsletter</h4>
				<p>Subscribe to our newsletter system now <br> to get latest news from us.</p>
				<form action="#">
					<input type="text" placeholder="Enter your email...">
				</form>
				<a href="#" class="btn">Subscribe now <i class="ion-ios-arrow-forward"></i></a>
			</div>
		</div>
	</div>
	<div class="ft-copyright">
		<div class="ft-left">
			<p>© 2017 Blockbuster. All Rights Reserved. Designed by leehari.</p>
		</div>
		<div class="backtotop">
			<p><a href="#" id="back-to-top">Back to top  <i class="ion-ios-arrow-thin-up"></i></a></p>
@endsection

@section('scripts')
<script>
// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchIcon = document.getElementById('search-icon');
    const searchInput = document.getElementById('search-query');
    const searchType = document.getElementById('search-type');
    
    function performSearch() {
        const query = searchInput.value.trim();
        const type = searchType.value;
        
        if (query) {
            if (type === 'movies') {
                const searchUrl = `{{ route('movies.search') }}?q=${encodeURIComponent(query)}`;
                window.location.href = searchUrl;
            } else {
                // For TV shows, redirect to home search
                const searchUrl = `{{ route('home.search') }}?q=${encodeURIComponent(query)}&type=${type}`;
                window.location.href = searchUrl;
            }
        }
    }
    
    // Search on icon click
    if (searchIcon) {
        searchIcon.addEventListener('click', performSearch);
    }
    
    // Search on Enter key press
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    }
});
</script>
@endsection
