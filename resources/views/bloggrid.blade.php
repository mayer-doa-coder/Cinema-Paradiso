@extends('layouts.app')

@section('title', 'Cinema Paradiso - News & Blog')

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
					<h1> blog listing - grid</h1>
					<ul class="breadcumb">
						<li class="active"><a href="{{ route('home') }}">Home</a></li>
						<li> <span class="ion-ios-arrow-right"></span> blog listing</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- blog grid section-->
<div class="page-single">
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-sm-12 col-xs-12">
				<div class="row">
					<div class="col-md-4 col-sm-12 col-xs-12">
						<div class="blog-item-style-2">
							<a href="blogdetail.html"><img src="images/uploads/blogv21.jpg" alt=""></a>
							<div class="blog-it-infor">
								<h3><a href="blogdetail.html">Godzilla: King Of The Monsters Adds O’Shea Jackson Jr</a></h3>
								<span class="time">27 Mar 2017</span>
								<p>Looks like Kong: Skull Island started a tradition with its casting of Straight ...</p>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-12 col-xs-12">
						<div class="blog-item-style-2">
							<a href="blogdetail.html"><img src="images/uploads/blogv22.jpg" alt=""></a>
							<div class="blog-it-infor">
								<h3><a href="blogdetail.html">First Official Images of Alicia Vikander As Tomb Raider’s Lara Croft</a></h3>
								<span class="time">27 Mar 2017</span>
								<p>Aside from the her digital incarnation, the most recognisable image of Tomb ...</p>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-12 col-xs-12">
						<div class="blog-item-style-2">
							<a href="blogdetail.html"><img src="images/uploads/blogv23.jpg" alt=""></a>
							<div class="blog-it-infor">
								<h3><a href="blogdetail.html">The Boss Baby Holds On At The Top US Box Office</a></h3>
								<span class="time">27 Mar 2017</span>
								<p>He might be a primary protector of New York City, but at heart, Peter Parker is ...</p>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 col-sm-12 col-xs-12">
						<div class="blog-item-style-2">
							<a href="blogdetail.html"><img src="images/uploads/blogv24.jpg" alt=""></a>
							<div class="blog-it-infor">
								<h3><a href="blogdetail.html">Stephen King and Owen King's Novel Sleeping Beauties Being</a></h3>
								<span class="time">27 Mar 2017</span>
								<p>It's starting to feel like the seventies and eighties all over again, with ...</p>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-12 col-xs-12">
						<div class="blog-item-style-2">
							<a href="blogdetail.html"><img src="images/uploads/blogv29.jpg" alt=""></a>
							<div class="blog-it-infor">
								<h3><a href="blogdetail.html">Pablo Larrain Directing Tom Hardy In The True American</a></h3>
								<span class="time">27 Mar 2017</span>
								<p>Back in 2014, it looked like Tom Hardy would be starring for Kathryn...</p>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-12 col-xs-12">
						<div class="blog-item-style-2">
							<a href="blogdetail.html"><img src="images/uploads/blog29.jpg" alt=""></a>
							<div class="blog-it-infor">
								<h3><a href="blogdetail.html">Michael Keaton Looking To Play A Villain In Dumbo</a></h3>
								<span class="time">27 Mar 2017</span>
								<p>Michael Keaton and Tim Burton haven't collaborated since Batman Returns, but .</p>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 col-sm-12 col-xs-12">
						<div class="blog-item-style-2">
							<a href="blogdetail.html"><img src="images/uploads/blog27.jpg" alt=""></a>
							<div class="blog-it-infor">
								<h3><a href="blogdetail.html">Sam Mendes In Talks To Develop Graphic Novel </a></h3>
								<span class="time">27 Mar 2017</span>
								<p>We already know that Shailene Woodley and Miles Teller have chemistry of ...</p>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-12 col-xs-12">
						<div class="blog-item-style-2">
							<a href="blogdetail.html"><img src="images/uploads/blog26.jpg" alt=""></a>
							<div class="blog-it-infor">
								<h3><a href="blogdetail.html">Eric Roth To Write Dune For Denis Villeneuve</a></h3>
								<span class="time">27 Mar 2017</span>
								<p>Though the idea of tackling Frank Herbert's imposing Dune books might send a ...</p>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-12 col-xs-12">
						<div class="blog-item-style-2">
							<a href="blogdetail.html"><img src="images/uploads/blogv25.jpg" alt=""></a>
							<div class="blog-it-infor">
								<h3><a href="blogdetail.html">John Simm Returning To Doctor Who As The Master</a></h3>
								<span class="time">27 Mar 2017</span>
								<p>John Simm's cackling, lunatic regeneration of The Master hasn't been seen on...</p>
							</div>
						</div>
					</div>
				</div>
            	<ul class="pagination">
            		<li class="icon-prev"><a href="#"><i class="ion-ios-arrow-left"></i></a></li>
            		<li class="active"><a href="#">1</a></li>
					<li><a href="#">2</a></li>
					<li><a href="#">3</a></li>
					<li><a href="#">4</a></li>
					<li><a href="#">...</a></li>
					<li><a href="#">21</a></li>
					<li><a href="#">22</a></li>
					<li class="icon-next"><a href="#"><i class="ion-ios-arrow-right"></i></a></li>
            	</ul>
			</div>
			<div class="col-md-3 col-sm-12 col-xs-12">
				<div class="sidebar">
					<div class="sb-search sb-it">
						<h4 class="sb-title">Search</h4>
						<input type="text" placeholder="Enter keywords">
					</div>
					<div class="sb-cate sb-it">
						<h4 class="sb-title">Categories</h4>
						<ul>
							<li><a href="#">Awards (50)</a></li>
							<li><a href="#">Box office (38)</a></li>
							<li><a href="#">Film reviews (72)</a></li>
							<li><a href="#">News (45)</a></li>
							<li><a href="#">Global (06)</a></li>
						</ul>
					</div>
					<div class="sb-recentpost sb-it">
						<h4 class="sb-title">most popular</h4>
						<div class="recent-item">
							<span>01</span><h6><a href="#">Korea Box Office: Beauty and the Beast Wins Fourth</a></h6>
						</div>
						<div class="recent-item">
							<span>02</span><h6><a href="#">Homeland Finale Includes Shocking Death </a></h6>
						</div>
						<div class="recent-item">
							<span>03</span><h6><a href="#">Fate of the Furious Reviews What the Critics Saying</a></h6>
						</div>
					</div>
					<div class="sb-tags sb-it">
						<h4 class="sb-title">tags</h4>
						<ul class="tag-items">
							<li><a href="#">Batman</a></li>
							<li><a href="#">film</a></li>
							<li><a href="#">homeland</a></li>
							<li><a href="#">Fast & Furious</a></li>
							<li><a href="#">Dead Walker</a></li>
							<li><a href="#">King</a></li>
							<li><a href="#">Beauty</a></li>
						</ul>
					</div>
					<div class="ads">
						<img src="images/uploads/ads1.png" alt="">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--end of  blog grid section-->
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
