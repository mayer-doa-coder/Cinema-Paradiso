@extends('layouts.app')

@section('title', 'Cinema Paradiso - Movie News & Blog')

@push('styles')
<style>
/* Remove white border/outline from navigation buttons */
header .navbar-default .navbar-nav li a,
header .navbar-default .navbar-nav li.btn a,
header .navbar-default .navbar-nav li a:focus,
header .navbar-default .navbar-nav li a:active,
header .navbar-default .navbar-nav li.btn a:focus,
header .navbar-default .navbar-nav li.btn a:active {
    outline: none !important;
    border: none !important;
    box-shadow: none !important;
    background-color: transparent !important;
}

/* Specific fix for sign up button */
header .navbar-default .navbar-nav li.btn a {
    background-color: #ec6eab !important;
}

/* Maintain hover effects */
header .navbar-default .navbar-nav li a:hover {
    color: #e9d736 !important;
}

header .navbar-default .navbar-nav li.btn a:hover {
    background-color: #d55a98 !important;
}

/* Remove focus ring from all buttons and links */
*:focus {
    outline: none !important;
}

button:focus,
a:focus,
input:focus,
select:focus,
textarea:focus {
    outline: none !important;
    box-shadow: none !important;
}

.blog-meta {
    margin-top: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.blog-type {
    padding: 2px 8px;
    border-radius: 3px;
    font-size: 10px;
    color: white;
}
.badge-primary {
    background-color: #007bff;
}
.badge-secondary {
    background-color: #6c757d;
}
.search-info {
    margin-bottom: 30px;
    padding: 15px;
    background-color: #0b1a2a;
    border-radius: 5px;
    border: 1px solid #405266;
}
.reddit-meta {
    margin-top: 10px;
}
.reddit-meta span {
    margin-right: 10px;
    font-size: 12px;
}
.upvotes {
    color: #0079d3;
}
.comments {
    color: #0079d3;
}
.author {
    color: #abb7c4;
}
.search-form {
    margin-bottom: 20px;
}

/* Fix blog grid layout - ensure equal heights and proper alignment */
.blog-grid-container {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -15px;
}

.blog-grid-item {
    padding: 0 15px;
    margin-bottom: 30px;
    display: flex;
}

.blog-item-style-2 {
    display: flex;
    flex-direction: column;
    height: 100%;
    background-color: #020d18;
    border-radius: 5px;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.blog-item-style-2:hover {
    transform: translateY(-5px);
}

.blog-item-style-2 > a {
    display: block;
    flex-shrink: 0;
}

.blog-item-style-2 .blog-it-infor {
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 15px;
}

.blog-item-style-2 .blog-it-infor h3 {
    margin-top: 0;
    margin-bottom: 10px;
    font-size: 16px;
    line-height: 1.4;
    min-height: 44px;
    flex-shrink: 0;
}

.blog-item-style-2 .blog-it-infor .time {
    display: block;
    margin-bottom: 10px;
    font-size: 12px;
    flex-shrink: 0;
}

.blog-item-style-2 .blog-it-infor p {
    flex: 1;
    margin-bottom: 10px;
    font-size: 13px;
    line-height: 1.5;
}

.blog-item-style-2 .blog-meta {
    margin-top: auto;
    padding-top: 10px;
    border-top: 1px solid #233a50;
    flex-shrink: 0;
}

/* Ensure 4 columns per row on desktop */
@media (min-width: 992px) {
    .blog-grid-item {
        width: 25%;
        flex: 0 0 25%;
        max-width: 25%;
    }
}

/* 2 columns on tablet */
@media (min-width: 768px) and (max-width: 991px) {
    .blog-grid-item {
        width: 50%;
        flex: 0 0 50%;
        max-width: 50%;
    }
}

/* 1 column on mobile */
@media (max-width: 767px) {
    .blog-grid-item {
        width: 100%;
        flex: 0 0 100%;
        max-width: 100%;
    }
}

/* Community Discussions Grid */
.discussion-section {
    margin-top: 40px;
	margin-bottom: 20px;
}

.discussion-grid-container {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -15px;
}

.discussion-grid-item {
    padding: 0 15px;
    margin-bottom: 30px;
    display: flex;
}

.discussion-grid-item .blog-item-style-2 {
    display: flex;
    flex-direction: column;
    height: 100%;
    background-color: #020d18;
    border-radius: 5px;
    border-left: 3px solid #de68bcff;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.discussion-grid-item .blog-item-style-2:hover {
    transform: translateY(-5px);
}

.discussion-grid-item .blog-it-infor {
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 15px;
}

.discussion-grid-item .blog-it-infor h4 {
    margin-top: 0;
    margin-bottom: 10px;
    font-size: 15px;
    line-height: 1.4;
    min-height: 42px;
    flex-shrink: 0;
}

.discussion-grid-item .blog-it-infor .time {
    display: block;
    margin-bottom: 10px;
    font-size: 12px;
    flex-shrink: 0;
}

.discussion-grid-item .reddit-meta {
    margin-top: auto;
    padding-top: 10px;
    border-top: 1px solid #233a50;
    flex-shrink: 0;
}

/* Ensure 4 columns per row on desktop for discussions */
@media (min-width: 992px) {
    .discussion-grid-item {
        width: 25%;
        flex: 0 0 25%;
        max-width: 25%;
    }
}

/* 2 columns on tablet for discussions */
@media (min-width: 768px) and (max-width: 991px) {
    .discussion-grid-item {
        width: 50%;
        flex: 0 0 50%;
        max-width: 50%;
    }
}

/* 1 column on mobile for discussions */
@media (max-width: 767px) {
    .discussion-grid-item {
        width: 100%;
        flex: 0 0 100%;
        max-width: 100%;
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
						<li class="first active">
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

<div class="hero mv-single-hero">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1 class="page-title">Movie News & Blog</h1>
				<p class="page-subtitle">Stay updated with the latest news from Hollywood and beyond</p>
			</div>
		</div>
	</div>
</div>

<!-- blog grid section-->
<div class="page-single">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				@if($error)
					<div class="alert alert-warning">
						{{ $error }}
					</div>
				@endif

				@if(isset($searchQuery))
					<div class="search-info">
						<h4>Search Results for: "{{ $searchQuery }}"</h4>
						<p>Found {{ $pagination['total'] }} articles</p>
					</div>
				@endif

				<div class="blog-grid-container">
					@forelse($articles as $article)
						<div class="blog-grid-item">
							<div class="blog-item-style-2">
								<a href="{{ $article['url'] }}" target="_blank" rel="noopener noreferrer">
									<img src="{{ $article['image'] }}" 
										 alt="{{ $article['title'] }}" 
										 style="width: 100%; height: 200px; object-fit: cover; border-radius: 5px;"
										 loading="lazy"
										 onerror="this.src='{{ asset('images/uploads/blog-it1.jpg') }}'">
								</a>
								<div class="blog-it-infor">
									<h3>
										<a href="{{ $article['url'] }}" target="_blank" rel="noopener noreferrer">
											{{ Str::limit($article['title'], 60) }}
										</a>
									</h3>
									<span class="time">
										{{ Carbon\Carbon::parse($article['published_at'])->format('d M Y') }}
										@if($article['source'])
											• {{ $article['source'] }}
										@endif
									</span>
									<p>{{ Str::limit($article['description'], 100) }}</p>
									<div class="blog-meta">
										@if($article['author'])
											<small class="author">By {{ $article['author'] }}</small>
										@endif
										<span class="blog-type badge badge-{{ $article['type'] === 'rss' ? 'primary' : 'secondary' }}">
											{{ strtoupper($article['type']) }}
										</span>
									</div>
								</div>
							</div>
						</div>
					@empty
						<div class="col-md-12">
							<div class="text-center">
								<h3>No articles found</h3>
								<p>We're working to bring you the latest movie news. Please check back later!</p>
							</div>
						</div>
					@endforelse
				</div>

				@if($discussions->count() > 0)
					<div class="discussion-section">
						<div class="row" style="margin-bottom: 20px;">
							<div class="col-md-12" style="margin-bottom: 20px;">
								<h3 style="color: #ffffff">Community Discussions</h3>
								<p>Popular movie discussions from Reddit</p>
							</div>
						</div>
						<div class="discussion-grid-container">
							@foreach($discussions->take(8) as $discussion)
								<div class="discussion-grid-item">
									<div class="blog-item-style-2">
										<div class="blog-it-infor">
											<h4>
												<a href="{{ $discussion['url'] }}" target="_blank" rel="noopener">
													{{ Str::limit($discussion['title'], 50) }}
												</a>
											</h4>
											<span class="time">
												{{ Carbon\Carbon::parse($discussion['created'])->format('d M Y') }}
												• r/{{ $discussion['subreddit'] }}
											</span>
											<div class="reddit-meta">
												<span class="upvotes">↑ {{ number_format($discussion['score']) }}</span>
												<span class="comments">💬 {{ $discussion['comments'] }}</span>
												<small class="author">u/{{ $discussion['author'] }}</small>
											</div>
										</div>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				@endif

				<!-- Pagination -->
				@if($pagination['last_page'] > 1)
					<ul class="pagination">
						@if($pagination['current_page'] > 1)
							<li class="icon-prev">
								<a href="?page={{ $pagination['current_page'] - 1 }}{{ isset($searchQuery) ? '&q=' . urlencode($searchQuery) : '' }}">
									<i class="ion-ios-arrow-left"></i>
								</a>
							</li>
						@endif
						
						@for($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['last_page'], $pagination['current_page'] + 2); $i++)
							<li class="{{ $i == $pagination['current_page'] ? 'active' : '' }}">
								<a href="?page={{ $i }}{{ isset($searchQuery) ? '&q=' . urlencode($searchQuery) : '' }}">
									{{ $i }}
								</a>
							</li>
						@endfor
						
						@if($pagination['current_page'] < $pagination['last_page'])
							<li class="icon-next">
								<a href="?page={{ $pagination['current_page'] + 1 }}{{ isset($searchQuery) ? '&q=' . urlencode($searchQuery) : '' }}">
									<i class="ion-ios-arrow-right"></i>
								</a>
							</li>
						@endif
					</ul>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search form submission
    const searchForm = document.querySelector('.search-form form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="q"]');
            if (!searchInput.value.trim()) {
                e.preventDefault();
                alert('Please enter a search term');
            }
        });
    }
});
</script>
@endpush

@push('styles')
<style>
.page-title {
    font-size: 3em;
    margin-bottom: 10px;
    color: #ffffff;
    text-align: center;
    font-weight: bold;
}

.page-subtitle {
    font-size: 1.2em;
    color: #abb7c4;
    text-align: center;
    margin-bottom: 30px;
}
</style>
@endpush