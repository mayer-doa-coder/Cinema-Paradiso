@extends('layouts.app')

@section('title', 'Cinema Paradiso - Help & Support')

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
						<li class="dropdown first">
							<a class="btn btn-default dropdown-toggle lv1" data-toggle="dropdown">
							Home <i class="fa fa-angle-down" aria-hidden="true"></i>
							</a>
							<ul class="dropdown-menu level1">
								<li><a href="{{ route('home') }}">Home 01</a></li>
								<li><a href="{{ route('home') }}">Home 02</a></li>
								<li><a href="{{ route('home') }}">Home 03</a></li>
							</ul>
						</li>
						<li class="dropdown first">
							<a class="btn btn-default dropdown-toggle lv1" data-toggle="dropdown" data-hover="dropdown">
							movies<i class="fa fa-angle-down" aria-hidden="true"></i>
							</a>
							<ul class="dropdown-menu level1">
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" >Movie grid<i class="ion-ios-arrow-forward"></i></a>
									<ul class="dropdown-menu level2">
										<li><a href="{{ route('moviegrid') }}">Movie grid</a></li>
										<li><a href="{{ route('moviegrid') }}">movie grid full width</a></li>
									</ul>
								</li>			
								<li><a href="{{ route('movielist') }}">Movie list</a></li>
								<li><a href="#">Movie single</a></li>
								<li class="it-last"><a href="#">Series single</a></li>
							</ul>
						</li>
						<li class="dropdown first">
							<a class="btn btn-default dropdown-toggle lv1" data-toggle="dropdown" data-hover="dropdown">
							celebrities <i class="fa fa-angle-down" aria-hidden="true"></i>
							</a>
							<ul class="dropdown-menu level1">
								<li><a href="celebritygrid01.html">celebrity grid 01</a></li>
								<li><a href="celebritygrid02.html">celebrity grid 02 </a></li>
								<li><a href="celebritylist.html">celebrity list</a></li>
								<li class="it-last"><a href="celebritysingle.html">celebrity single</a></li>
							</ul>
						</li>
						<li class="dropdown first">
							<a class="btn btn-default dropdown-toggle lv1" data-toggle="dropdown" data-hover="dropdown">
							news <i class="fa fa-angle-down" aria-hidden="true"></i>
							</a>
							<ul class="dropdown-menu level1">
								<li><a href="bloglist.html">blog List</a></li>
								<li><a href="bloggrid.html">blog Grid</a></li>
								<li class="it-last"><a href="blogdetail.html">blog Detail</a></li>
							</ul>
						</li>
						<li class="dropdown first">
							<a class="btn btn-default dropdown-toggle lv1" data-toggle="dropdown" data-hover="dropdown">
							community <i class="fa fa-angle-down" aria-hidden="true"></i>
							</a>
							<ul class="dropdown-menu level1">
								<li><a href="userfavoritegrid.html">user favorite grid</a></li>
								<li><a href="userfavoritelist.html">user favorite list</a></li>
								<li><a href="userprofile.html">user profile</a></li>
								<li class="it-last"><a href="userrate.html">user rate</a></li>
							</ul>
						</li>
					</ul>
					<ul class="nav navbar-nav flex-child-menu menu-right">               
						<li><a href="{{ route('help') }}">Help</a></li>
						<li class="loginLink"><a href="#">LOG In</a></li>
						<li class="btn signupLink"><a href="#">sign up</a></li>
					</ul>
				</div>
	    </nav>
	    
	    <!-- top search form -->
	    <div class="top-search">
	    	<form action="{{ route('movies.search') }}" method="GET">
		    	<div class="search-dropdown">
		    		<i class="ion-ios-list-outline"></i>
			    	<select name="type">
						<option value="movies">Movies</option>
						<option value="tvshows">TV Shows</option>
					</select>
				</div>
				<div class="search-input">
					<input type="text" name="q" placeholder="Search for a movie, TV Show that you are looking for" value="{{ request('q') }}">
					<button type="submit" style="background: none; border: none; color: inherit;">
						<i class="ion-ios-search"></i>
					</button>
				</div>
			</form>
	    </div>
	</div>
</header>
<!-- END | Header -->
 
<!-- Help content section -->
<div class="help-content">
	<div class="container">
		<div class="page-header">
			<h1>Help & Support Center</h1>
			<p>Welcome to Cinema Paradiso Help Center. Find answers to frequently asked questions and get support for your movie experience.</p>
		</div>
		
		<div class="row">
			<div class="col-md-8">
				<!-- FAQ Section -->
				<div class="help-section">
					<h2>Frequently Asked Questions</h2>
					
					<div class="faq-category">
						<h3>Getting Started</h3>
						<div class="faq-item">
							<h4>How do I create an account?</h4>
							<p>Click on the "Sign Up" button in the top right corner of any page. Fill in your username, email, and password. Make sure your password contains at least 8 characters with uppercase, lowercase, and numbers or special characters.</p>
						</div>
						<div class="faq-item">
							<h4>How do I search for movies?</h4>
							<p>Use the search bar at the top of the page. You can search by movie title, actor name, director, or genre. Our advanced search will help you find exactly what you're looking for.</p>
						</div>
						<div class="faq-item">
							<h4>Can I watch movies for free?</h4>
							<p>No. Cinema Paradiso exclusively serves as a platform for film logging and reviews, not for streaming or screening movies.</p>
						</div>
					</div>

					<div class="faq-category">
						<h3>Account Management</h3>
						<div class="faq-item">
							<h4>How do I reset my password?</h4>
							<p>Click on "Log In" and then "Forget password?". Enter your email address and we'll send you instructions to reset your password.</p>
						</div>
						<div class="faq-item">
							<h4>How do I update my profile information?</h4>
							<p>Once logged in, go to your user profile page where you can update your personal information, preferences, and watchlist.</p>
						</div>
						<div class="faq-item">
							<h4>How do I delete my account?</h4>
							<p>Contact our support team through the contact form below, and we'll help you with account deletion while ensuring your data privacy.</p>
						</div>
					</div>

					<div class="faq-category">
						<h3>Movie Features</h3>
						<div class="faq-item">
							<h4>How do I add movies to my watchlist?</h4>
							<p>When viewing a movie page, click the heart icon or "Add to Watchlist" button. You can access your watchlist from your user profile.</p>
						</div>
						<div class="faq-item">
							<h4>How do I rate movies?</h4>
							<p>On any movie page, you'll find a rating section where you can give stars and write reviews. Your ratings help other users discover great content.</p>
						</div>
						<div class="faq-item">
							<h4>How do I follow celebrities?</h4>
							<p>Visit a celebrity's profile page and click the "Follow" button. You'll receive updates on their latest movies and activities.</p>
						</div>
					</div>

					<div class="faq-category">
						<h3>Technical Support</h3>
						<div class="faq-item">
							<h4>Why won't videos play?</h4>
							<p>Ensure you have a stable internet connection and that JavaScript is enabled in your browser. Try refreshing the page or clearing your browser cache.</p>
						</div>
						<div class="faq-item">
							<h4>What browsers are supported?</h4>
							<p>Cinema Paradiso works best on the latest versions of Chrome, Firefox, Safari, and Edge. Make sure your browser is up to date for the best experience.</p>
						</div>
						<div class="faq-item">
							<h4>How do I report a bug?</h4>
							<p>If you encounter any issues, please use the contact form below to report bugs. Provide as much detail as possible to help us resolve the issue quickly.</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Contact & Support Sidebar -->
			<div class="col-md-4">
				<div class="sidebar">
					<!-- Contact Information -->
					<div class="sb-it contact-info-box">
						<h4 class="sb-title">Contact Information</h4>
						<div class="contact-details">
							<div class="contact-item">
								<i class="ion-android-call"></i>
								<div class="contact-text">
									<h6>Call Us</h6>
									<a href="tel:+8801326503869">+8801326503869</a>
								</div>
							</div>
							<div class="contact-item">
								<i class="ion-android-mail"></i>
								<div class="contact-text">
									<h6>Email Support</h6>
									<a href="mailto:ttawhid401@gmail.com">ttawhid401@gmail.com</a>
								</div>
							</div>
							<div class="contact-item">
								<i class="ion-location"></i>
								<div class="contact-text">
									<h6>Address</h6>
									<p>KUET, Khulna<br>Bangladesh</p>
								</div>
							</div>
						</div>
						<p class="support-hours">Support Hours: 24/7 Online Support</p>
					</div>

					<!-- Contact Form -->
					<div class="sb-it contact-form-box">
						<h4 class="sb-title">Send Us a Message</h4>
						<form class="contact-form" method="post" action="{{ route('contact.send') }}">
							@csrf
							@if(session('success'))
								<div class="alert alert-success">
									{{ session('success') }}
								</div>
							@endif
							@if(session('error'))
								<div class="alert alert-danger">
									{{ session('error') }}
								</div>
							@endif
							@if($errors->any())
								<div class="alert alert-danger">
									<ul class="error-list">
										@foreach($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
								</div>
							@endif
							<div class="form-group">
								<label for="contact-name">Your Name *</label>
								<input type="text" id="contact-name" name="name" class="form-control" placeholder="Enter your full name" required>
							</div>
							<div class="form-group">
								<label for="contact-email">Email Address *</label>
								<input type="email" id="contact-email" name="email" class="form-control" placeholder="your@email.com" required>
							</div>
							<div class="form-group">
								<label for="contact-subject">Subject *</label>
								<select id="contact-subject" name="subject" class="form-control" required>
									<option value="">Select a topic</option>
									<option value="general">General Inquiry</option>
									<option value="technical">Technical Support</option>
									<option value="account">Account Issues</option>
									<option value="billing">Billing Question</option>
									<option value="feedback">Feedback & Suggestions</option>
									<option value="bug">Report a Bug</option>
									<option value="other">Other</option>
								</select>
							</div>
							<div class="form-group">
								<label for="contact-message">Message *</label>
								<textarea id="contact-message" name="message" class="form-control" rows="5" placeholder="Please describe your issue or question in detail..." required></textarea>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-primary">Send Message <i class="ion-ios-arrow-forward"></i></button>
							</div>
						</form>
					</div>

					<!-- Quick Links -->
					<div class="sb-it quick-links-box">
						<h4 class="sb-title">Quick Links</h4>
						<ul class="quick-links">
							<li><a href="#faq"><i class="ion-help-circled"></i> FAQ</a></li>
							<li><a href="#"><i class="ion-person"></i> My Account</a></li>
							<li><a href="#"><i class="ion-heart"></i> My Watchlist</a></li>
							<li><a href="#"><i class="ion-star"></i> My Ratings</a></li>
							<li><a href="#"><i class="ion-document-text"></i> News & Updates</a></li>
							<li><a href="#"><i class="ion-settings"></i> Privacy Policy</a></li>
							<li><a href="#"><i class="ion-information-circled"></i> Terms of Service</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection