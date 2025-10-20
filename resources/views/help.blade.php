@extends('layouts.app')

@section('title', 'Cinema Paradiso - Help & Support')

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
	    
	    @include('partials._search')
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
						
						<!-- Message Display Areas -->
						<div id="form-messages" style="display: none;">
							<div id="success-message" class="alert alert-success" style="display: none;"></div>
							<div id="error-message" class="alert alert-danger" style="display: none;"></div>
						</div>
						
						<form id="contact-form" class="contact-form">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="form-group">
								<label for="contact-name">Your Name *</label>
								<input type="text" id="contact-name" name="name" class="form-control" placeholder="Enter your full name" required>
								<div class="field-error" id="name-error" style="color: red; font-size: 12px; margin-top: 5px; display: none;"></div>
							</div>
							<div class="form-group">
								<label for="contact-email">Email Address *</label>
								<input type="email" id="contact-email" name="email" class="form-control" placeholder="your@email.com" required>
								<div class="field-error" id="email-error" style="color: red; font-size: 12px; margin-top: 5px; display: none;"></div>
							</div>
							<div class="form-group">
								<label for="contact-message">Message *</label>
								<textarea id="contact-message" name="message" class="form-control" rows="5" placeholder="Please describe your issue or question in detail..." required></textarea>
								<div class="field-error" id="message-error" style="color: red; font-size: 12px; margin-top: 5px; display: none;"></div>
							</div>
							<div class="form-group">
								<button type="submit" id="submit-btn" class="btn btn-primary">
									<span id="btn-text">Send Message</span>
									<span id="btn-loading" style="display: none;">Sending...</span>
									<i class="ion-ios-arrow-forward"></i>
								</button>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contact-form');
    const submitBtn = document.getElementById('submit-btn');
    const btnText = document.getElementById('btn-text');
    const btnLoading = document.getElementById('btn-loading');
    const formMessages = document.getElementById('form-messages');
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-message');

    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Clear previous messages and errors
        clearMessages();
        
        // Show loading state
        setLoadingState(true);
        
        // Get form data
        const formData = new FormData(contactForm);
        
        // Get CSRF token from meta tag as fallback
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value;
        
        // Ensure CSRF token is included
        if (csrfToken) {
            formData.set('_token', csrfToken);
        }
        
        // Submit form via fetch
        fetch('{{ route("contact.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            setLoadingState(false);
            
            if (data.success) {
                showSuccessMessage(data.message);
                contactForm.reset();
            } else {
                showErrorMessage(data.message);
                
                // Show field-specific errors
                if (data.errors) {
                    showFieldErrors(data.errors);
                }
            }
        })
        .catch(error => {
            setLoadingState(false);
            showErrorMessage('An unexpected error occurred. Please try again.');
            console.error('Error:', error);
        });
    });
    
    function setLoadingState(loading) {
        if (loading) {
            submitBtn.disabled = true;
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline';
        } else {
            submitBtn.disabled = false;
            btnText.style.display = 'inline';
            btnLoading.style.display = 'none';
        }
    }
    
    function clearMessages() {
        formMessages.style.display = 'none';
        successMessage.style.display = 'none';
        errorMessage.style.display = 'none';
        
        // Clear field errors
        document.querySelectorAll('.field-error').forEach(error => {
            error.style.display = 'none';
            error.textContent = '';
        });
    }
    
    function showSuccessMessage(message) {
        successMessage.textContent = message;
        successMessage.style.display = 'block';
        formMessages.style.display = 'block';
        
        // Scroll to message
        formMessages.scrollIntoView({ behavior: 'smooth' });
    }
    
    function showErrorMessage(message) {
        errorMessage.textContent = message;
        errorMessage.style.display = 'block';
        formMessages.style.display = 'block';
        
        // Scroll to message
        formMessages.scrollIntoView({ behavior: 'smooth' });
    }
    
    function showFieldErrors(errors) {
        for (const field in errors) {
            const errorElement = document.getElementById(field + '-error');
            if (errorElement) {
                errorElement.textContent = errors[field][0];
                errorElement.style.display = 'block';
            }
        }
    }
});
</script>
@endpush

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