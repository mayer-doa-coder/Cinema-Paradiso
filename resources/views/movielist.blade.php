@extends('layouts.app')

@section('title', 'Cinema Paradiso - Home')

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
							<ul class="sub-menu">
								<li><a href="{{ route('movies.index', ['category' => 'popular']) }}">Popular</a></li>
								<li><a href="{{ route('movies.index', ['category' => 'top-rated']) }}">Top Rated</a></li>
								<li><a href="{{ route('movies.index', ['category' => 'trending']) }}">Trending</a></li>
								<li><a href="{{ route('movies.index', ['category' => 'upcoming']) }}">Upcoming</a></li>
							</ul>
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
		    	<select>
					<option value="movies">Movies</option>
					<option value="tvshows">TV Shows</option>
				</select>
			</div>
			<div class="search-input">
				<input type="text" placeholder="Search for a movie, TV Show that you are looking for">
				<i class="ion-ios-search"></i>
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
					<h1> movie listing - list</h1>
					<ul class="breadcumb">
						<li class="active"><a href="{{ route('home') }}">Home</a></li>
						<li> <span class="ion-ios-arrow-right"></span> movie listing</li>
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
					<p>Found <span>1,608 movies</span> in total</p>
					<label>Sort by:</label>
					<select>
						<option value="popularity">Popularity Descending</option>
						<option value="popularity">Popularity Ascending</option>
						<option value="rating">Rating Descending</option>
						<option value="rating">Rating Ascending</option>
						<option value="date">Release date Descending</option>
						<option value="date">Release date Ascending</option>
					</select>
					<a href="{{ route('movies.index') }}" class="list"><i class="ion-ios-list-outline active"></i></a>
					<a  href="{{ route('movies.index') }}" class="grid"><i class="ion-grid"></i></a>
				</div>
				<div class="movie-item-style-2">
					<img src="{{ asset('images/uploads/mv1.jpg') }}" alt="">
					<div class="mv-item-infor">
						<h6><a href="#">oblivion <span>(2012)</span></a></h6>
						<p class="rate"><i class="ion-android-star"></i><span>8.1</span> /10</p>
						<p class="describe">Earth's mightiest heroes must come together and learn to fight as a team if they are to stop the mischievous Loki and his alien army from enslaving humanity...</p>
						<p class="run-time"> Run Time: 2h21’    .     <span>MMPA: PG-13 </span>    .     <span>Release: 1 May 2015</span></p>
						<p>Director: <a href="#">Joss Whedon</a></p>
						<p>Stars: <a href="#">Robert Downey Jr.,</a> <a href="#">Chris Evans,</a> <a href="#">  Chris Hemsworth</a></p>
					</div>
				</div>
				<div class="movie-item-style-2">
					<img src="{{ asset('images/uploads/mv2.jpg') }}" alt="">
					<div class="mv-item-infor">
						<h6><a href="#">into the wild <span>(2014)</span></a></h6>
						<p class="rate"><i class="ion-android-star"></i><span>7.8</span> /10</p>
						<p class="describe">As Steve Rogers struggles to embrace his role in the modern world, he teams up with a fellow Avenger and S.H.I.E.L.D agent, Black Widow, to battle a new threat...</p>
						<p class="run-time"> Run Time: 2h21’    .     <span>MMPA: PG-13 </span>    .     <span>Release: 1 May 2015</span></p>
						<p>Director: <a href="#">Anthony Russo,</a><a href="#">Joe Russo</a></p>
						<p>Stars: <a href="#">Chris Evans,</a> <a href="#">Samuel L. Jackson,</a> <a href="#">  Scarlett Johansson</a></p>
					</div>
				</div>
				<div class="movie-item-style-2">
					<img src="{{ asset('images/uploads/mv3.jpg') }}" alt="">
					<div class="mv-item-infor">
						<h6><a href="#">blade runner  <span>(2015)</span></a></h6>
						<p class="rate"><i class="ion-android-star"></i><span>7.3</span> /10</p>
						<p class="describe">Armed with a super-suit with the astonishing ability to shrink in scale but increase in strength, cat burglar Scott Lang must embrace his inner hero and help...</p>
						<p class="run-time"> Run Time: 2h21’    .     <span>MMPA: PG-13 </span>    .     <span>Release: 1 May 2015</span></p>
						<p>Director: <a href="#">Peyton Reed</a></p>
						<p>Stars: <a href="#">Paul Rudd,</a> <a href="#"> Michael Douglas</a></p>
					</div>
				</div>
				<div class="movie-item-style-2">
					<img src="{{ asset('images/uploads/mv4.jpg') }}" alt="">
					<div class="mv-item-infor">
						<h6><a href="#">Mulholland pride<span> (2013)  </span></a></h6>
						<p class="rate"><i class="ion-android-star"></i><span>7.2</span> /10</p>
						<p class="describe">When Tony Stark's world is torn apart by a formidable terrorist called the Mandarin, he starts an odyssey of rebuilding and retribution.</p>
						<p class="run-time"> Run Time: 2h21’    .     <span>MMPA: PG-13 </span>    .     <span>Release: 1 May 2015</span></p>
						<p>Director: <a href="#">Shane Black</a></p>
						<p>Stars: <a href="#">Robert Downey Jr., </a> <a href="#">  Guy Pearce,</a><a href="#">Don Cheadle</a></p>
					</div>
				</div>
				<div class="movie-item-style-2">
					<img src="{{ asset('images/uploads/mv5.jpg') }}" alt="">
					<div class="mv-item-infor">
						<h6><a href="#">skyfall: evil of boss<span> (2013)  </span></a></h6>
						<p class="rate"><i class="ion-android-star"></i><span>7.0</span> /10</p>
						<p class="describe">When Tony Stark's world is torn apart by a formidable terrorist called the Mandarin, he starts an odyssey of rebuilding and retribution.</p>
						<p class="run-time"> Run Time: 2h21’    .     <span>MMPA: PG-13 </span>    .     <span>Release: 1 May 2015</span></p>
						<p>Director: <a href="#">Alan Taylor</a></p>
						<p>Stars: <a href="#">Chris Hemsworth,  </a> <a href="#">  Natalie Portman,</a><a href="#">Tom Hiddleston</a></p>
					</div>
				</div>
				<div class="topbar-filter">
					<label>Movies per page:</label>
					<select>
						<option value="range">5 Movies</option>
						<option value="saab">10 Movies</option>
					</select>
					<div class="pagination2">
						<span>Page 1 of 2:</span>
						<a class="active" href="#">1</a>
						<a href="#">2</a>
						<a href="#"><i class="ion-arrow-right-b"></i></a>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-12 col-xs-12">
				<div class="sidebar">
					<div class="searh-form">
						<h4 class="sb-title">Search for movie</h4>
						<form class="form-style-1" action="{{ route('movies.search') }}" method="GET">
							<div class="row">
								<div class="col-md-12 form-it">
									<label>Movie name</label>
									<input type="text" name="q" placeholder="Enter keywords" value="{{ request('q') }}">
								</div>
								<div class="col-md-12 form-it">
									<label>Genres & Subgenres</label>
									<div class="group-ip">
										<select name="genre" class="ui fluid dropdown">
											<option value="">Enter to filter genres</option>
											<option value="Action" {{ request('genre') == 'Action' ? 'selected' : '' }}>Action</option>
					                        <option value="Comedy" {{ request('genre') == 'Comedy' ? 'selected' : '' }}>Comedy</option>
					                        <option value="Drama" {{ request('genre') == 'Drama' ? 'selected' : '' }}>Drama</option>
					                        <option value="Horror" {{ request('genre') == 'Horror' ? 'selected' : '' }}>Horror</option>
					                        <option value="Sci-Fi" {{ request('genre') == 'Sci-Fi' ? 'selected' : '' }}>Sci-Fi</option>
										</select>
									</div>
									
								</div>
								<div class="col-md-12 form-it">
									<label>Rating Range</label>
									
									 <select name="rating">
										<option value="">-- Select the rating range below --</option>
										<option value="9-10" {{ request('rating') == '9-10' ? 'selected' : '' }}>9.0 - 10.0</option>
										<option value="8-9" {{ request('rating') == '8-9' ? 'selected' : '' }}>8.0 - 8.9</option>
										<option value="7-8" {{ request('rating') == '7-8' ? 'selected' : '' }}>7.0 - 7.9</option>
										<option value="6-7" {{ request('rating') == '6-7' ? 'selected' : '' }}>6.0 - 6.9</option>
									</select>
									
								</div>
								<div class="col-md-12 form-it">
									<label>Release Year</label>
									<div class="row">
										<div class="col-md-6">
											<select name="year_from">
												<option value="">From</option>
												<option value="2020" {{ request('year_from') == '2020' ? 'selected' : '' }}>2020</option>
												<option value="2015" {{ request('year_from') == '2015' ? 'selected' : '' }}>2015</option>
												<option value="2010" {{ request('year_from') == '2010' ? 'selected' : '' }}>2010</option>
												<option value="2005" {{ request('year_from') == '2005' ? 'selected' : '' }}>2005</option>
												<option value="2000" {{ request('year_from') == '2000' ? 'selected' : '' }}>2000</option>
											</select>
										</div>
										<div class="col-md-6">
											<select name="year_to">
												<option value="">To</option>
												<option value="2024" {{ request('year_to') == '2024' ? 'selected' : '' }}>2024</option>
												<option value="2020" {{ request('year_to') == '2020' ? 'selected' : '' }}>2020</option>
												<option value="2015" {{ request('year_to') == '2015' ? 'selected' : '' }}>2015</option>
												<option value="2010" {{ request('year_to') == '2010' ? 'selected' : '' }}>2010</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-12 ">
									<input class="submit" type="submit" value="Search">
								</div>
							</div>
						</form>
					</div>
					<div class="ads">
						<img src="{{ asset('images/uploads/ads1.png') }}" alt="">
					</div>
					<div class="sb-popular-reviewers sb-it">
						<h4 class="sb-title">Popular Reviewers</h4>
						<div class="reviewer-list">
							<div class="reviewer-item">
								<div class="reviewer-avatar">
									<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
										<circle cx="20" cy="20" r="20" fill="#e9d736"/>
										<circle cx="20" cy="15" r="6" fill="#1a1a1a"/>
										<path d="M10 32c0-5.5 4.5-10 10-10s10 4.5 10 10" fill="#1a1a1a"/>
									</svg>
								</div>
								<div class="reviewer-info">
									<h6>Movie Critic Pro</h6>
									<p>152 Reviews • 4.8★</p>
								</div>
							</div>
							<div class="reviewer-item">
								<div class="reviewer-avatar">
									<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
										<circle cx="20" cy="20" r="20" fill="#3e9fd8"/>
										<circle cx="20" cy="15" r="6" fill="#1a1a1a"/>
										<path d="M10 32c0-5.5 4.5-10 10-10s10 4.5 10 10" fill="#1a1a1a"/>
									</svg>
								</div>
								<div class="reviewer-info">
									<h6>Cinema Expert</h6>
									<p>89 Reviews • 4.6★</p>
								</div>
							</div>
							<div class="reviewer-item">
								<div class="reviewer-avatar">
									<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
										<circle cx="20" cy="20" r="20" fill="#666"/>
										<circle cx="20" cy="15" r="6" fill="#1a1a1a"/>
										<path d="M10 32c0-5.5 4.5-10 10-10s10 4.5 10 10" fill="#1a1a1a"/>
									</svg>
								</div>
								<div class="reviewer-info">
									<h6>Film Enthusiast</h6>
									<p>67 Reviews • 4.5★</p>
								</div>
							</div>
						</div>
					</div>
					<div class="sb-popular-blogs sb-it">
						<h4 class="sb-title">Popular Blogs</h4>
						<div class="blog-list">
							<div class="blog-item">
								<h6><a href="#">Top 10 Movies to Watch This Month</a></h6>
								<p>Discover the most anticipated films...</p>
								<span class="blog-date">Dec 15, 2024</span>
							</div>
							<div class="blog-item">
								<h6><a href="#">Behind the Scenes: Making of Blockbusters</a></h6>
								<p>Exclusive insights into movie production...</p>
								<span class="blog-date">Dec 12, 2024</span>
							</div>
							<div class="blog-item">
								<h6><a href="#">Award Season Predictions 2025</a></h6>
								<p>Our expert predictions for this year's awards...</p>
								<span class="blog-date">Dec 10, 2024</span>
							</div>
							<div class="blog-item">
								<h6><a href="#">Classic Movies Every Film Lover Should See</a></h6>
								<p>A curated list of timeless cinema...</p>
								<span class="blog-date">Dec 8, 2024</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- footer section-->
<footer class="ht-footer">
	<div class="container">
		<div class="flex-parent-ft">
			<div class="flex-child-ft item1">
				 <a href="{{ route('home') }}"><img class="logo" src="{{ asset('images/cinema_paradiso.png') }}" alt=""></a>
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
					<li><a href="{{ route('help') }}">Help Center</a></li>
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
		</div>
	</div>
</footer>
<!-- end of footer section-->

<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/plugins.js') }}"></script>
<script src="{{ asset('js/plugins2.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
</body>
</html>
