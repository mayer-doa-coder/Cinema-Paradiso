@extends('layouts.app')

@section('title', 'User Profile - Cinema Paradiso')

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
.form-style-1.user-pro {
    padding: 30px;
    border-radius: 5px;
}
.form-style-1 h4 {
    color: #3e9fd8;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #405266;
}
.form-it {
    margin-bottom: 20px;
}
.form-it label {
    color: #ffffff;
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
}
.form-it input,
.form-it select {
    width: 100%;
    padding: 10px 15px;
    background: #020d18;
    border: 1px solid #405266;
    border-radius: 3px;
    color: #abb7c4;
    transition: all 0.3s ease;
}
.form-it input:focus,
.form-it select:focus {
    border-color: #e9d736;
    outline: none;
}
.form-it input::placeholder {
    color: #405266;
}
.form-style-1 form.user {
    margin-bottom: 40px;
}
.form-style-1 input.submit {
    background: #eb70ac;
    color: #fff;
    border: none;
    padding: 12px 40px;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    text-align: center;
    display: block;
    width: 150px;
    margin: 0;
}
.form-style-1 input.submit:hover {
    background: #eb70ac;
    color: #0b1a2a;
}
.movie-card:hover > div {
    border-color: #e9d736 !important;
    transform: translateY(-2px);
}
.movie-card .remove-movie:hover {
    background: #eb70ac !important;
    transform: scale(1.1);
}
.movie-search-item {
    padding: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    border-bottom: 1px solid #405266;
}
.movie-search-item:last-child {
    border-bottom: none;
}
.movie-search-item:hover {
    background: #020d18;
}
.movie-search-item img {
    width: 50px;
    height: 75px;
    object-fit: cover;
    border-radius: 3px;
}
.movie-search-item .movie-info {
    flex: 1;
}
.movie-search-item .movie-info h5 {
    color: #fff;
    font-size: 14px;
    margin: 0 0 5px 0;
    font-weight: 500;
}
.movie-search-item .movie-info p {
    color: #abb7c4;
    font-size: 12px;
    margin: 0;
}
.form-style-1 form.favorites {
    margin-bottom: 40px;
}
#selected-movies {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 15px;
}
@media (max-width: 992px) {
    #selected-movies {
        grid-template-columns: repeat(3, 1fr);
    }
}
@media (max-width: 576px) {
    #selected-movies {
        grid-template-columns: repeat(2, 1fr);
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
						<li> <span class="ion-ios-arrow-right"></span>Profile</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="page-single">
	<div class="container">
		<div class="row ipad-width">
			<div class="col-md-3 col-sm-12 col-xs-12">
				@include('profile.partials.sidebar')
			</div>
			<div class="col-md-9 col-sm-12 col-xs-12">
				<div class="form-style-1 user-pro">
					@if(session('success'))
						<div class="alert alert-success" style="background: #eb70ac; color: #fff; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #eb70ac;">
							{{ session('success') }}
						</div>
					@endif
					
					@if($errors->any())
						<div class="alert alert-danger" style="background: #eb70ac; color: #fff; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #eb70ac;">
							<ul style="margin: 0; padding-left: 20px;">
								@foreach($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					
					<form action="{{ route('user.profile.update') }}" method="POST" class="user">
						@csrf
						<h4>Profile details</h4>
						<div class="row">
							<div class="col-md-6 form-it">
								<label>Username</label>
								<input type="text" name="username" value="{{ Auth::user()->username ?? Auth::user()->name }}" placeholder="Username">
							</div>
							<div class="col-md-6 form-it">
								<label>Email Address</label>
								<input type="email" name="email" value="{{ Auth::user()->email }}" placeholder="Email Address" required>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 form-it">
								<label>First Name</label>
								<input type="text" name="first_name" value="{{ explode(' ', Auth::user()->name)[0] ?? '' }}" placeholder="First Name">
							</div>
							<div class="col-md-6 form-it">
								<label>Last Name</label>
								<input type="text" name="last_name" value="{{ implode(' ', array_slice(explode(' ', Auth::user()->name), 1)) ?? '' }}" placeholder="Last Name">
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 form-it">
								<label>Phone Number</label>
								<input type="tel" name="phone" value="{{ Auth::user()->phone ?? '' }}" placeholder="+1 234 567 8900">
							</div>
							<div class="col-md-6 form-it">
								<label>Country</label>
								<select name="country" id="country-select">
									<option value="">Select Country</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 form-it">
								<label>State/Province</label>
								<select name="state" id="state-select">
									<option value="">Select State/Province</option>
								</select>
							</div>
							<div class="col-md-6 form-it">
								<label>Bio</label>
								<textarea name="bio" placeholder="Tell us about yourself" style="width: 100%; padding: 10px 15px; background: #020d18; border: 1px solid #405266; border-radius: 3px; color: #abb7c4; min-height: 100px; resize: vertical;">{{ Auth::user()->bio ?? '' }}</textarea>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input class="submit" type="submit" value="Save">
							</div>
						</div>	
					</form>
					
					<form action="{{ route('user.favorites.update') }}" method="POST" class="favorites" id="favorite-movies">
						@csrf
						<h4>Top 5 Favorite Movies</h4>
						<p style="color: #abb7c4; margin-bottom: 20px; font-size: 14px;">
							These movies will be displayed on your community profile. Search and select up to 5 movies.
						</p>
						
						<div class="row">
							<div class="col-md-12 form-it">
								<label>Search Movies</label>
								<div style="position: relative;">
									<input type="text" id="movie-search" placeholder="Type to search movies..." autocomplete="off">
									<div id="movie-search-results" style="position: absolute; top: 100%; left: 0; right: 0; background: #0b1a2a; border: 1px solid #405266; border-radius: 3px; max-height: 300px; overflow-y: auto; display: none; z-index: 1000;">
									</div>
								</div>
							</div>
						</div>
						
						<div class="row" style="margin-top: 20px;">
							<div class="col-md-12">
								<div id="selected-movies" style="margin-bottom: 20px;">
									@foreach(Auth::user()->favoriteMovies()->limit(5)->get() as $movie)
									<div class="movie-card" data-movie-id="{{ $movie->movie_id }}">
										<div style="position: relative; border-radius: 5px; overflow: hidden; border: 2px solid #405266; transition: all 0.3s ease;">
											<img src="{{ $movie->poster_url }}" alt="{{ $movie->movie_title }}" style="width: 100%; height: 180px; object-fit: cover; display: block;">
											<button type="button" class="remove-movie" style="position: absolute; top: 5px; right: 5px; background: rgba(235, 112, 172, 0.9); color: #fff; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer; font-size: 16px; line-height: 1; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;">
											
											</button>
											<div style="padding: 8px; background: rgba(2, 13, 24, 0.95);">
												<p style="color: #fff; font-size: 12px; margin: 0; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $movie->movie_title }}</p>
											</div>
										</div>
										<input type="hidden" name="favorite_movies[]" value="{{ $movie->movie_id }}">
										<input type="hidden" name="movie_titles[]" value="{{ $movie->movie_title }}">
										<input type="hidden" name="movie_posters[]" value="{{ $movie->movie_poster }}">
									</div>
									@endforeach
								</div>
								<p id="movie-limit-message" style="color: #405266; font-size: 13px; margin-bottom: 20px;">
									<span id="movie-count">{{ Auth::user()->favoriteMovies()->count() }}</span> / 5 movies selected
								</p>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-12">
								<input class="submit" type="submit" value="Favorite">
							</div>
						</div>	
					</form>
					
					<form action="{{ route('user.password.update') }}" method="POST" class="password" id="change-password">
						@csrf
						<h4>Change password</h4>
						<div class="row">
							<div class="col-md-6 form-it">
								<label>Old Password</label>
								<input type="password" name="old_password" placeholder="**********">
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 form-it">
								<label>New Password</label>
								<input type="password" name="new_password" placeholder="***************">
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 form-it">
								<label>Confirm New Password</label>
								<input type="password" name="new_password_confirmation" placeholder="***************">
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input class="submit" type="submit" value="Change">
							</div>
						</div>	
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/countries-states.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========== Country/State Selection ==========
    const countrySelect = document.getElementById('country-select');
    const stateSelect = document.getElementById('state-select');
    const currentCountry = "{{ Auth::user()->country ?? '' }}";
    const currentState = "{{ Auth::user()->state ?? '' }}";
    
    // Populate countries dropdown
    const countries = getCountries();
    countries.forEach(country => {
        const option = document.createElement('option');
        option.value = country;
        option.textContent = country;
        if (country === currentCountry) {
            option.selected = true;
        }
        countrySelect.appendChild(option);
    });
    
    // Function to update states dropdown
    function updateStates(country) {
        // Clear existing states
        stateSelect.innerHTML = '<option value="">Select State/Province</option>';
        
        if (country) {
            const states = getStates(country);
            if (states.length > 0) {
                states.forEach(state => {
                    const option = document.createElement('option');
                    option.value = state;
                    option.textContent = state;
                    if (state === currentState) {
                        option.selected = true;
                    }
                    stateSelect.appendChild(option);
                });
                stateSelect.disabled = false;
            } else {
                stateSelect.disabled = true;
                stateSelect.innerHTML = '<option value="">No states available</option>';
            }
        } else {
            stateSelect.disabled = true;
        }
    }
    
    // Initialize states if country is already selected
    if (currentCountry) {
        updateStates(currentCountry);
    }
    
    // Update states when country changes
    countrySelect.addEventListener('change', function() {
        updateStates(this.value);
    });
    
    // ========== Movie Search & Selection ==========
    const movieSearch = document.getElementById('movie-search');
    const searchResults = document.getElementById('movie-search-results');
    const selectedMoviesContainer = document.getElementById('selected-movies');
    const movieCountDisplay = document.getElementById('movie-count');
    let searchTimeout;
    const MAX_MOVIES = 5;
    
    // Get currently selected movie IDs
    function getSelectedMovieIds() {
        const movieCards = selectedMoviesContainer.querySelectorAll('.movie-card');
        return Array.from(movieCards).map(card => card.dataset.movieId);
    }
    
    // Update movie count display
    function updateMovieCount() {
        const count = selectedMoviesContainer.querySelectorAll('.movie-card').length;
        movieCountDisplay.textContent = count;
        
        // Disable search if max reached
        if (count >= MAX_MOVIES) {
            movieSearch.disabled = true;
            movieSearch.placeholder = 'Maximum 5 movies selected';
        } else {
            movieSearch.disabled = false;
            movieSearch.placeholder = 'Type to search movies...';
        }
    }
    
    // Search movies using TMDb API
    movieSearch.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }
        
        searchTimeout = setTimeout(() => {
            fetch(`https://api.themoviedb.org/3/search/movie?api_key={{ config('services.tmdb.api_key') }}&query=${encodeURIComponent(query)}&language=en-US&page=1`)
                .then(response => response.json())
                .then(data => {
                    displaySearchResults(data.results);
                })
                .catch(error => {
                    console.error('Error searching movies:', error);
                });
        }, 300);
    });
    
    // Display search results
    function displaySearchResults(movies) {
        if (!movies || movies.length === 0) {
            searchResults.innerHTML = '<div style="padding: 15px; color: #abb7c4; text-align: center;">No movies found</div>';
            searchResults.style.display = 'block';
            return;
        }
        
        const selectedIds = getSelectedMovieIds();
        
        searchResults.innerHTML = movies.slice(0, 10).map(movie => {
            const isSelected = selectedIds.includes(movie.id.toString());
            const posterPath = movie.poster_path 
                ? `https://image.tmdb.org/t/p/w92${movie.poster_path}`
                : '{{ asset("images/uploads/no-image.png") }}';
            
            return `
                <div class="movie-search-item ${isSelected ? 'disabled' : ''}" 
                     data-movie-id="${movie.id}"
                     data-movie-title="${movie.title.replace(/"/g, '&quot;')}"
                     data-movie-poster="${movie.poster_path || ''}"
                     style="${isSelected ? 'opacity: 0.5; cursor: not-allowed;' : ''}">
                    <img src="${posterPath}" alt="${movie.title}">
                    <div class="movie-info">
                        <h5>${movie.title}</h5>
                        <p>${movie.release_date ? movie.release_date.substring(0, 4) : 'N/A'} ${isSelected ? '(Already selected)' : ''}</p>
                    </div>
                </div>
            `;
        }).join('');
        
        searchResults.style.display = 'block';
        
        // Add click handlers
        searchResults.querySelectorAll('.movie-search-item:not(.disabled)').forEach(item => {
            item.addEventListener('click', function() {
                addMovie(this);
            });
        });
    }
    
    // Add movie to favorites
    function addMovie(element) {
        const currentCount = selectedMoviesContainer.querySelectorAll('.movie-card').length;
        
        if (currentCount >= MAX_MOVIES) {
            alert('You can only select up to 5 favorite movies.');
            return;
        }
        
        const movieId = element.dataset.movieId;
        const movieTitle = element.dataset.movieTitle;
        const moviePoster = element.dataset.moviePoster;
        const posterUrl = moviePoster 
            ? `https://image.tmdb.org/t/p/w500${moviePoster}`
            : '{{ asset("images/uploads/no-image.png") }}';
        
        // Create movie card
        const movieCard = document.createElement('div');
        movieCard.className = 'movie-card';
        movieCard.dataset.movieId = movieId;
        movieCard.innerHTML = `
            <div style="position: relative; border-radius: 5px; overflow: hidden; border: 2px solid #405266; transition: all 0.3s ease;">
                <img src="${posterUrl}" alt="${movieTitle}" style="width: 100%; height: 180px; object-fit: cover; display: block;">
                <button type="button" class="remove-movie" style="position: absolute; top: 5px; right: 5px; background: rgba(235, 112, 172, 0.9); color: #fff; border: none; border-radius: 50%; width: 25px; height: 25px; cursor: pointer; font-size: 16px; line-height: 1; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;">
                    ×
                </button>
                <div style="padding: 8px; background: rgba(2, 13, 24, 0.95);">
                    <p style="color: #fff; font-size: 12px; margin: 0; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${movieTitle}</p>
                </div>
            </div>
            <input type="hidden" name="favorite_movies[]" value="${movieId}">
            <input type="hidden" name="movie_titles[]" value="${movieTitle}">
            <input type="hidden" name="movie_posters[]" value="${moviePoster}">
        `;
        
        selectedMoviesContainer.appendChild(movieCard);
        
        // Add remove handler
        movieCard.querySelector('.remove-movie').addEventListener('click', function() {
            movieCard.remove();
            updateMovieCount();
        });
        
        // Clear search
        movieSearch.value = '';
        searchResults.style.display = 'none';
        
        updateMovieCount();
    }
    
    // Add remove handlers to existing movies
    document.querySelectorAll('.remove-movie').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.movie-card').remove();
            updateMovieCount();
        });
    });
    
    // Hide search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!movieSearch.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
    
    // Initialize movie count
    updateMovieCount();
});
</script>
@endpush
