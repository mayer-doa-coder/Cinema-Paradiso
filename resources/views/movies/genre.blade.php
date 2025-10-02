@extends('layouts.app')

@section('title', $genre_name)

@section('content')
<div class="hero hero3">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- hero content -->
                <div class="hero-ct">
                    <h1>{{ $genre_name }}</h1>
                    <ul class="breadcumb">
                        <li class="active"><a href="{{ route('home') }}">Home</a></li>
                        <li><span class="ion-ios-arrow-right"></span><a href="{{ route('movies.index') }}">Movies</a></li>
                        <li><span class="ion-ios-arrow-right"></span>{{ $genre_name }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-single">
    <div class="container">
        <div class="row ipad-width2">
            <div class="col-md-8 col-sm-12 col-xs-12">
                <div class="topbar-filter">
                    <p>Found <span>{{ count($movies) }} movies</span> in {{ $genre_name }}</p>
                </div>

                @if($error)
                    <div class="alert alert-danger">
                        <i class="ion-alert-circled"></i> {{ $error }}
                    </div>
                @endif

                @if(!empty($movies))
                    <div class="flex-wrap-movielist">
                        @foreach($movies as $movie)
                            <div class="movie-item-style-2 movie-item-style-1">
                                <img src="{{ app('App\Services\MovieService')->getImageUrl($movie['poster_path'] ?? null) }}" 
                                     alt="{{ $movie['title'] ?? 'Movie Poster' }}">
                                <div class="hvr-inner">
                                    <a href="{{ route('movies.show', $movie['id']) }}">Read more <i class="ion-android-arrow-dropright"></i></a>
                                </div>
                                <div class="mv-item-infor">
                                    <h6><a href="{{ route('movies.show', $movie['id']) }}">{{ $movie['title'] ?? 'Untitled' }}</a></h6>
                                    <p class="rate">
                                        @php
                                            $rating = app('App\Services\MovieService')->getRatingStars($movie['vote_average'] ?? 0);
                                        @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $rating)
                                                <i class="ion-android-star"></i>
                                            @else
                                                <i class="ion-android-star-outline"></i>
                                            @endif
                                        @endfor
                                        <span class="fr">{{ number_format($movie['vote_average'] ?? 0, 1) }}/10</span>
                                    </p>
                                    @if(isset($movie['release_date']))
                                        <p class="time">{{ date('Y', strtotime($movie['release_date'])) }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($total_pages > 1)
                        <div class="topbar-filter">
                            <div class="pagination2">
                                @if($current_page > 1)
                                    <span><a href="{{ route('movies.genre', ['genreId' => $genre_id, 'page' => $current_page - 1]) }}">
                                        <i class="ion-arrow-left-b"></i></a></span>
                                @endif

                                @php
                                    $start = max(1, $current_page - 2);
                                    $end = min($total_pages, $current_page + 2);
                                @endphp

                                @for($i = $start; $i <= $end; $i++)
                                    <span><a href="{{ route('movies.genre', ['genreId' => $genre_id, 'page' => $i]) }}" 
                                        class="{{ $i == $current_page ? 'active' : '' }}">{{ $i }}</a></span>
                                @endfor

                                @if($current_page < $total_pages)
                                    <span><a href="{{ route('movies.genre', ['genreId' => $genre_id, 'page' => $current_page + 1]) }}">
                                        <i class="ion-arrow-right-b"></i></a></span>
                                @endif
                            </div>
                        </div>
                    @endif
                @else
                    <div class="no-results">
                        <h3>No movies found in {{ $genre_name }}</h3>
                        <p>Try browsing other genres or check back later for new content.</p>
                        <a href="{{ route('movies.index') }}" class="btn">Browse All Movies</a>
                    </div>
                @endif
            </div>

            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="sidebar">
                    <div class="searh-form">
                        <h4 class="sb-title">Search for Movies</h4>
                        <form class="form-style-1" method="GET" action="{{ route('movies.search') }}">
                            <div class="row">
                                <div class="col-md-12 form-it">
                                    <label>Movie name</label>
                                    <input type="text" name="q" placeholder="Enter keywords here" value="{{ request('q') }}">
                                </div>
                                <div class="col-md-12 form-it">
                                    <input class="submit" type="submit" value="Search">
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="category-filter">
                        <h4 class="sb-title">Browse Categories</h4>
                        <div class="filter-links">
                            <a href="{{ route('movies.index', ['category' => 'popular']) }}">Popular Movies</a>
                            <a href="{{ route('movies.index', ['category' => 'top-rated']) }}">Top Rated</a>
                            <a href="{{ route('movies.index', ['category' => 'trending']) }}">Trending</a>
                            <a href="{{ route('movies.index', ['category' => 'upcoming']) }}">Upcoming</a>
                        </div>
                    </div>

                    <div class="ads">
                        <img src="{{ asset('images/uploads/ads1.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Add any genre-specific JavaScript here
    $(document).ready(function() {
        // Initialize genre functionality
    });
</script>
@endsection