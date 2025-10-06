@extends('layouts.app')

@section('title', 'Cinema Paradiso - Movie News & Blog')

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
    background-color: #f8f9fa;
    border-radius: 5px;
}
.reddit-meta {
    margin-top: 10px;
}
.reddit-meta span {
    margin-right: 10px;
    font-size: 12px;
}
.upvotes {
    color: #ff4500;
}
.comments {
    color: #0079d3;
}
.author {
    color: #666;
}
.search-form {
    margin-bottom: 20px;
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
							<a class="btn btn-default lv1" href="{{ route('community') }}">
							Community
							</a>
						</li>
						<li class="dropdown first">
							<a class="btn btn-default dropdown-toggle lv1" data-toggle="dropdown" data-hover="dropdown">
							Blog <i class="fa fa-angle-down" aria-hidden="true"></i>
							</a>
							<ul class="dropdown-menu level1">
								<li><a href="{{ route('bloggrid') }}">Blog Grid</a></li>
								<li><a href="{{ route('blogdetail') }}">Blog Detail</a></li>
							</ul>
						</li>

					</ul>
					<ul class="nav navbar-nav flex-child-menu menu-right">

						<li class="loginLink dropdown">
							<a class="btn btn-default dropdown-toggle lv1" data-toggle="dropdown" data-hover="dropdown">
							Login <i class="fa fa-angle-down" aria-hidden="true"></i>
							</a>
							<ul class="dropdown-menu level1">
								<li class="it-login">
									<div class="row">
										<div class="col-md-12">
											<h4>User Login</h4>
											<form class="login-form" id="loginForm" method="POST" action="{{ route('auth.login') }}">
												@csrf
												<div class="form-group">
													<label>Username or Email: <span>*</span></label>
													<input type="text" name="email" placeholder="Enter your email">
												</div>
												<div class="form-group">
													<label>Password: <span>*</span></label>
													<input type="password" name="password" placeholder="Enter your password">
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-md-6">
															<div class="checkbox">
																<label>
																  <input type="checkbox"> Remember me
																</label>
															</div>
														</div>
														<div class="col-md-6 forgotpwd-content">
															<a href="#">Forgot password</a>
														</div>

													</div>

												</div>
												<div class="login-condition">
													<p>*All fields are required. By clicking "Log In" you accept our <a href="#">Terms and Conditions.</a></p>
												</div>
												<div class="form-group">
													<button class="btn" type="submit">Login</button>
												</div>
											</form>
										</div>
									</div>
								</li>
							</ul>
						</li>
						<li class="searchLink">
							<a class="btn btn-default lv1" href="#">
								<i class="fa fa-search" aria-hidden="true"></i>
							</a>
							<div class="top-search">
								@include('partials._search')
							</div>
						</li>
					</ul>
				</div>
		</nav>

		@if(!empty($randomWallpaper) && is_array($randomWallpaper))
			@php $wallpaper = collect($randomWallpaper)->random(); @endphp
			<div class="slider sliderv2" style="background: url('{{ $wallpaper['backdrop_url'] }}') no-repeat; background-size: cover; background-position: center;">
				<div class="container">
					<div class="row">
						<div class="slider-single-item">
							<div class="slider-it">
								<div class="slider-item">
									<div class="slider-it-content">
										<div class="hero-it-content">
											<div class="hero-cat">
												<div class="entry-date">
													<a href="#" class="bg-primary">Movie News</a>
												</div>
											</div>
											<div class="hero-it-infor">
												<h1><a href="#">Latest Movie News & Updates</a></h1>
												<p>Stay updated with the latest news from Hollywood and beyond. From box office reports to exclusive interviews, we bring you all the entertainment news that matters.</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endif
	</div>
</header>
<!-- END | Header -->

<div class="hero hero3">
	<div class="container">
		<div class="row">
			<div class="hero-content">
				<div class="hero-cap">
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
				<!-- Search Form -->
				<div class="search-form">
					<form method="GET" action="{{ route('blog.search') }}">
						<div class="input-group">
							<input type="text" name="q" class="form-control" placeholder="Search movie news..." value="{{ request('q') }}">
							<span class="input-group-btn">
								<button class="btn btn-primary" type="submit">Search</button>
							</span>
						</div>
					</form>
				</div>

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

				<div class="row">
					@forelse($articles as $article)
						<div class="col-md-4 col-sm-12 col-xs-12">
							<div class="blog-item-style-2">
								<a href="{{ route('blogdetail', ['url' => urlencode($article['url']), 'title' => urlencode($article['title'])]) }}">
									<img src="{{ $article['image'] ?? asset('images/uploads/default-blog.jpg') }}" 
										 alt="{{ $article['title'] }}" 
										 style="width: 100%; height: 200px; object-fit: cover;">
								</a>
								<div class="blog-it-infor">
									<h3>
										<a href="{{ route('blogdetail', ['url' => urlencode($article['url']), 'title' => urlencode($article['title'])]) }}">
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
					<div class="row" style="margin-top: 40px;">
						<div class="col-md-12">
							<h3>Community Discussions</h3>
							<p>Popular movie discussions from Reddit</p>
						</div>
						@foreach($discussions->take(6) as $discussion)
							<div class="col-md-4 col-sm-12 col-xs-12">
								<div class="blog-item-style-2" style="border-left: 3px solid #ff6b08;">
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

			<!-- Sidebar -->
			<div class="col-md-3 col-sm-12 col-xs-12">
				<div class="sidebar">
					<div class="sb-search sb-it">
						<h4 class="sb-title">Search News</h4>
						<form method="GET" action="{{ route('blog.search') }}">
							<input type="text" name="q" placeholder="Enter keywords" value="{{ request('q') }}">
							<button type="submit"><i class="fa fa-search"></i></button>
						</form>
					</div>
					<div class="sb-cate sb-it">
						<h4 class="sb-title">Categories</h4>
						<ul>
							<li><a href="{{ route('blog') }}">All News</a></li>
							<li><a href="{{ route('blog.search', ['q' => 'box office']) }}">Box Office</a></li>
							<li><a href="{{ route('blog.search', ['q' => 'trailer']) }}">Trailers</a></li>
							<li><a href="{{ route('blog.search', ['q' => 'interview']) }}">Interviews</a></li>
							<li><a href="{{ route('blog.search', ['q' => 'review']) }}">Reviews</a></li>
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