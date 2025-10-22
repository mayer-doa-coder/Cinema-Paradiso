@extends('layouts.app')

@section('title', 'My Lists - Cinema Paradiso')

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
    border: 3px solid #e9d736;
}
.user-img .redbtn {
    background: #eb70ac;
    color: #fff;
    padding: 8px 20px;
    border-radius: 5px;
    display: inline-block;
    transition: all 0.3s ease;
}
.user-img .redbtn:hover {
    background: #eb70ac;
    color: #0b1a2a;
}
.user-fav {
    margin-bottom: 20px;
}
.user-fav p {
    color: #3e9fd8;
    font-weight: 600;
    font-size: 16px;
    text-align: left !important;
    margin-bottom: 10px;
    padding-bottom: 10px;
    padding-left: 0 !important;
    border-bottom: 1px solid #405266;
}
.user-fav ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.user-fav ul li {
    margin-bottom: 8px;
}
.user-fav ul li a {
    color: #abb7c4;
    padding: 8px 15px;
    display: block;
    border-radius: 3px;
    transition: all 0.3s ease;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
    font-size: 13px;
}
.user-fav ul li.active a {
    color: #dcf836;
}
.user-fav ul li a:hover {
    color: #dcf836;
}
.list-container {
    background: #0b1a2a;
    padding: 30px;
    border-radius: 5px;
    margin-bottom: 30px;
}
.list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #405266;
}
.list-header h4 {
    color: #3e9fd8;
    font-size: 20px;
    margin: 0;
}
.create-list-btn {
    background: #eb70ac;
    color: #fff;
    padding: 10px 25px;
    border-radius: 5px;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
    border: none;
    cursor: pointer;
}
.create-list-btn:hover {
    background: #d55a92;
    color: #fff;
}
.list-item {
    background: rgba(15, 33, 51, 0.5);
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
    border: 1px solid #405266;
}
.list-item:hover {
    background: rgba(15, 33, 51, 0.8);
    border-color: #dcf836;
}
.list-item-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 10px;
}
.list-item-title {
    color: #fff;
    font-size: 20px;
    font-weight: 600;
    margin: 0 0 8px 0;
}
.list-item-title a {
    color: #fff;
    text-decoration: none;
    transition: color 0.3s ease;
}
.list-item-title a:hover {
    color: #dcf836;
}
.list-item-meta {
    color: #abb7c4;
    font-size: 13px;
    margin: 0 0 10px 0;
}
.list-item-tags {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}
.list-tag {
    background: #405266;
    color: #dcf836;
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}
.list-item-description {
    color: #ccc;
    font-size: 14px;
    line-height: 1.6;
    margin: 10px 0;
}
.list-item-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}
.list-item-actions a,
.list-item-actions button {
    padding: 6px 15px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 13px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}
.btn-view {
    background: #3e9fd8;
    color: #fff;
}
.btn-view:hover {
    background: #2e8fc8;
    color: #fff;
}
.btn-edit {
    background: #405266;
    color: #fff;
}
.btn-edit:hover {
    background: #506376;
    color: #fff;
}
.btn-delete {
    background: #eb70ac;
    color: #fff;
}
.btn-delete:hover {
    background: #d55a92;
    color: #fff;
}
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #abb7c4;
}
.empty-state i {
    font-size: 80px;
    color: #405266;
    margin-bottom: 20px;
    display: block;
}
.empty-state h3 {
    color: #e9d736;
    margin-bottom: 10px;
}
.empty-state p {
    margin-bottom: 30px;
}
.list-movies {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding: 15px 0;
    margin: 15px 0;
    scrollbar-width: thin;
    scrollbar-color: #405266 #0b1a2a;
}
.list-movies::-webkit-scrollbar {
    height: 8px;
}
.list-movies::-webkit-scrollbar-track {
    background: #0b1a2a;
    border-radius: 4px;
}
.list-movies::-webkit-scrollbar-thumb {
    background: #405266;
    border-radius: 4px;
}
.list-movies::-webkit-scrollbar-thumb:hover {
    background: #506376;
}
.list-movie-poster {
    flex-shrink: 0;
    width: 120px;
    height: 180px;
    border-radius: 5px;
    overflow: hidden;
    position: relative;
    border: 2px solid #405266;
    transition: all 0.3s ease;
}
.list-movie-poster:hover {
    border-color: #dcf836;
    transform: translateY(-3px);
}
.list-movie-poster img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.8);
}
.modal-content {
    background-color: #0b1a2a;
    margin: 50px auto;
    padding: 30px;
    border: 1px solid #405266;
    border-radius: 8px;
    width: 90%;
    max-width: 600px;
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #405266;
}
.modal-header h3 {
    color: #3e9fd8;
    margin: 0;
    font-size: 22px;
}
.close {
    color: #abb7c4;
    font-size: 32px;
    font-weight: bold;
    cursor: pointer;
    line-height: 1;
    transition: color 0.3s ease;
}
.close:hover {
    color: #dcf836;
}
.form-group {
    margin-bottom: 20px;
}
.form-group label {
    color: #fff;
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
}
.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    background: #020d18;
    border: 1px solid #405266;
    border-radius: 5px;
    color: #abb7c4;
    font-size: 14px;
    transition: all 0.3s ease;
}
.form-group input:focus,
.form-group textarea:focus {
    border-color: #dcf836;
    outline: none;
}
.form-group textarea {
    min-height: 100px;
    resize: vertical;
}
.form-group small {
    color: #abb7c4;
    font-size: 12px;
    display: block;
    margin-top: 5px;
}
.movie-search-container {
    position: relative;
}
.selected-movies-container {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 15px;
    padding: 15px;
    background: #020d18;
    border-radius: 5px;
    min-height: 60px;
}
.selected-movie-item {
    position: relative;
    width: 80px;
    height: 120px;
    border-radius: 4px;
    overflow: hidden;
    border: 2px solid #405266;
}
.selected-movie-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.selected-movie-item .remove-btn {
    position: absolute;
    top: 2px;
    right: 2px;
    background: rgba(235, 112, 172, 0.95);
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    cursor: pointer;
    font-size: 14px;
    line-height: 1;
    display: flex;
    align-items: center;
    justify-content: center;
}
.movie-search-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #0b1a2a;
    border: 1px solid #405266;
    border-radius: 5px;
    max-height: 300px;
    overflow-y: auto;
    display: none;
    z-index: 1000;
    margin-top: 5px;
}
.movie-search-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px;
    cursor: pointer;
    transition: background 0.3s ease;
    border-bottom: 1px solid #405266;
}
.movie-search-item:last-child {
    border-bottom: none;
}
.movie-search-item:hover {
    background: #020d18;
}
.movie-search-item img {
    width: 40px;
    height: 60px;
    object-fit: cover;
    border-radius: 3px;
}
.movie-search-item .movie-info h5 {
    color: #fff;
    font-size: 14px;
    margin: 0 0 4px 0;
}
.movie-search-item .movie-info p {
    color: #abb7c4;
    font-size: 12px;
    margin: 0;
}
.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #405266;
}
.btn-cancel {
    background: #405266;
    color: #fff;
    padding: 10px 25px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}
.btn-cancel:hover {
    background: #506376;
}
.btn-submit {
    background: #eb70ac;
    color: #fff;
    padding: 10px 30px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
}
.btn-submit:hover {
    background: #d55a92;
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
						<li> <span class="ion-ios-arrow-right"></span>Lists</li>
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
				<div class="list-container">
					<div class="list-header">
						<h4>My Lists</h4>
						<button class="create-list-btn" onclick="openCreateListModal()">
							<i class="ion-plus"></i> Create New List
						</button>
					</div>

					<div id="lists-container">
						<!-- Lists will be dynamically added here -->
					</div>

					<div class="empty-state" id="empty-state">
						<i class="ion-clipboard"></i>
						<h3>No Lists Created Yet</h3>
						<p>Create custom lists to organize your favorite movies and share them with others!</p>
						<button class="create-list-btn" onclick="openCreateListModal()">
							Create Your First List
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Create List Modal -->
<div id="createListModal" class="modal">
	<div class="modal-content">
		<div class="modal-header">
			<h3>Create New List</h3>
			<span class="close" onclick="closeCreateListModal()">&times;</span>
		</div>
		<form id="createListForm" onsubmit="createList(event)">
			<div class="form-group">
				<label for="listName">List Name *</label>
				<input type="text" id="listName" name="listName" required placeholder="e.g., 31 Days of HORROR">
			</div>
			
			<div class="form-group">
				<label for="listTags">Tags</label>
				<input type="text" id="listTags" name="listTags" placeholder="e.g., horror, thriller, action (comma-separated)">
				<small>Separate tags with commas</small>
			</div>
			
			<div class="form-group">
				<label for="listDescription">Description *</label>
				<textarea id="listDescription" name="listDescription" required placeholder="A brief description of your list..."></textarea>
			</div>
			
			<div class="form-group">
				<label for="movieSearch">Add Movies</label>
				<div class="movie-search-container">
					<input type="text" id="movieSearch" placeholder="Search for movies to add..." autocomplete="off">
					<div id="movieSearchResults" class="movie-search-results"></div>
				</div>
				<div id="selectedMovies" class="selected-movies-container">
					<p style="color: #abb7c4; font-size: 13px; margin: 0; width: 100%; text-align: center;">No movies selected yet</p>
				</div>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn-cancel" onclick="closeCreateListModal()">Cancel</button>
				<button type="submit" class="btn-submit">Create List</button>
			</div>
		</form>
	</div>
</div>

@push('scripts')
<script>
let selectedMovies = [];
let searchTimeout;
let lists = JSON.parse(localStorage.getItem('userLists')) || [];

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
	displayLists();
	setupMovieSearch();
});

function openCreateListModal() {
	document.getElementById('createListModal').style.display = 'block';
	document.body.style.overflow = 'hidden';
}

function closeCreateListModal() {
	document.getElementById('createListModal').style.display = 'none';
	document.body.style.overflow = 'auto';
	document.getElementById('createListForm').reset();
	selectedMovies = [];
	updateSelectedMoviesDisplay();
}

function setupMovieSearch() {
	const searchInput = document.getElementById('movieSearch');
	const resultsDiv = document.getElementById('movieSearchResults');
	
	searchInput.addEventListener('input', function() {
		const query = this.value.trim();
		
		clearTimeout(searchTimeout);
		
		if (query.length < 2) {
			resultsDiv.style.display = 'none';
			return;
		}
		
		searchTimeout = setTimeout(() => {
			fetch(`https://api.themoviedb.org/3/search/movie?api_key={{ config('services.tmdb.api_key') }}&query=${encodeURIComponent(query)}&language=en-US&page=1`)
				.then(response => response.json())
				.then(data => {
					displayMovieResults(data.results);
				})
				.catch(error => {
					console.error('Error searching movies:', error);
				});
		}, 300);
	});
	
	// Close results when clicking outside
	document.addEventListener('click', function(e) {
		if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
			resultsDiv.style.display = 'none';
		}
	});
}

function displayMovieResults(movies) {
	const resultsDiv = document.getElementById('movieSearchResults');
	
	if (!movies || movies.length === 0) {
		resultsDiv.innerHTML = '<div style="padding: 15px; color: #abb7c4; text-align: center;">No movies found</div>';
		resultsDiv.style.display = 'block';
		return;
	}
	
	resultsDiv.innerHTML = movies.slice(0, 8).map(movie => {
		const isSelected = selectedMovies.some(m => m.id === movie.id);
		const posterPath = movie.poster_path 
			? `https://image.tmdb.org/t/p/w92${movie.poster_path}`
			: '{{ asset("images/uploads/no-image.png") }}';
		
		return `
			<div class="movie-search-item ${isSelected ? 'disabled' : ''}" 
			     onclick="addMovieToList(${movie.id}, '${movie.title.replace(/'/g, "\\'")}', '${movie.poster_path || ''}', '${movie.release_date ? movie.release_date.substring(0, 4) : ''}')"
			     style="${isSelected ? 'opacity: 0.5; cursor: not-allowed;' : ''}">
				<img src="${posterPath}" alt="${movie.title}">
				<div class="movie-info">
					<h5>${movie.title}</h5>
					<p>${movie.release_date ? movie.release_date.substring(0, 4) : 'N/A'} ${isSelected ? '(Added)' : ''}</p>
				</div>
			</div>
		`;
	}).join('');
	
	resultsDiv.style.display = 'block';
}

function addMovieToList(id, title, poster, year) {
	if (selectedMovies.some(m => m.id === id)) {
		return;
	}
	
	selectedMovies.push({ id, title, poster, year });
	updateSelectedMoviesDisplay();
	document.getElementById('movieSearchResults').style.display = 'none';
	document.getElementById('movieSearch').value = '';
}

function removeMovieFromList(id) {
	selectedMovies = selectedMovies.filter(m => m.id !== id);
	updateSelectedMoviesDisplay();
}

function updateSelectedMoviesDisplay() {
	const container = document.getElementById('selectedMovies');
	
	if (selectedMovies.length === 0) {
		container.innerHTML = '<p style="color: #abb7c4; font-size: 13px; margin: 0; width: 100%; text-align: center;">No movies selected yet</p>';
		return;
	}
	
	container.innerHTML = selectedMovies.map(movie => {
		const posterUrl = movie.poster 
			? `https://image.tmdb.org/t/p/w185${movie.poster}`
			: '{{ asset("images/uploads/no-image.png") }}';
		
		return `
			<div class="selected-movie-item">
				<img src="${posterUrl}" alt="${movie.title}">
				<button type="button" class="remove-btn" onclick="removeMovieFromList(${movie.id})">×</button>
			</div>
		`;
	}).join('');
}

function createList(event) {
	event.preventDefault();
	
	const listName = document.getElementById('listName').value.trim();
	const listTags = document.getElementById('listTags').value.trim();
	const listDescription = document.getElementById('listDescription').value.trim();
	
	if (!listName || !listDescription) {
		alert('Please fill in all required fields');
		return;
	}
	
	const newList = {
		id: Date.now(),
		name: listName,
		tags: listTags.split(',').map(tag => tag.trim()).filter(tag => tag),
		description: listDescription,
		movies: selectedMovies,
		createdAt: new Date().toISOString(),
		movieCount: selectedMovies.length
	};
	
	lists.unshift(newList);
	localStorage.setItem('userLists', JSON.stringify(lists));
	
	displayLists();
	closeCreateListModal();
}

function displayLists() {
	const container = document.getElementById('lists-container');
	const emptyState = document.getElementById('empty-state');
	
	if (lists.length === 0) {
		emptyState.style.display = 'block';
		container.innerHTML = '';
		return;
	}
	
	emptyState.style.display = 'none';
	
	container.innerHTML = lists.map(list => {
		const timeAgo = getTimeAgo(new Date(list.createdAt));
		
		return `
			<div class="list-item">
				<div class="list-item-header">
					<div style="flex: 1;">
						<h5 class="list-item-title">
							<a href="javascript:void(0)">${list.name}</a>
						</h5>
						<p class="list-item-meta">${list.movieCount} films • ${timeAgo}</p>
						${list.tags.length > 0 ? `
							<div class="list-item-tags">
								${list.tags.map(tag => `<span class="list-tag">${tag}</span>`).join('')}
							</div>
						` : ''}
					</div>
				</div>
				<p class="list-item-description">${list.description}</p>
				
				${list.movies.length > 0 ? `
					<div class="list-movies">
						${list.movies.map(movie => {
							const posterUrl = movie.poster 
								? `https://image.tmdb.org/t/p/w185${movie.poster}`
								: '{{ asset("images/uploads/no-image.png") }}';
							return `
								<div class="list-movie-poster">
									<img src="${posterUrl}" alt="${movie.title}" title="${movie.title} (${movie.year})">
								</div>
							`;
						}).join('')}
					</div>
				` : ''}
				
				<div class="list-item-actions">
					<a href="javascript:void(0)" class="btn-view" onclick="viewList(${list.id})">View List</a>
					<a href="javascript:void(0)" class="btn-edit" onclick="editList(${list.id})">Edit</a>
					<button class="btn-delete" onclick="deleteList(${list.id})">Delete</button>
				</div>
			</div>
		`;
	}).join('');
}

function getTimeAgo(date) {
	const seconds = Math.floor((new Date() - date) / 1000);
	
	const intervals = {
		year: 31536000,
		month: 2592000,
		week: 604800,
		day: 86400,
		hour: 3600,
		minute: 60
	};
	
	for (const [unit, secondsInUnit] of Object.entries(intervals)) {
		const interval = Math.floor(seconds / secondsInUnit);
		if (interval >= 1) {
			return `Created ${interval} ${unit}${interval > 1 ? 's' : ''} ago`;
		}
	}
	
	return 'Created just now';
}

function viewList(id) {
	const list = lists.find(l => l.id === id);
	if (list) {
		alert(`Viewing: ${list.name}\n\nThis feature will show the full list details with all movies.`);
	}
}

function editList(id) {
	alert('Edit functionality coming soon!');
}

function deleteList(id) {
	if (confirm('Are you sure you want to delete this list?')) {
		lists = lists.filter(l => l.id !== id);
		localStorage.setItem('userLists', JSON.stringify(lists));
		displayLists();
	}
}

// Close modal when clicking outside
window.onclick = function(event) {
	const modal = document.getElementById('createListModal');
	if (event.target == modal) {
		closeCreateListModal();
	}
}
</script>
@endpush
@endsection
