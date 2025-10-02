{{-- Small Movie Card Partial - For carousel/slider usage --}}
<div class="slide-it">
    <div class="movie-item">
        <div class="mv-img">
            <img src="{{ app('App\Services\MovieService')->getImageUrl($movie['poster_path'] ?? null, 'w300') }}" 
                 alt="{{ $movie['title'] ?? 'Movie Poster' }}" 
                 width="185" 
                 height="284">
        </div>
        <div class="hvr-inner">
            <a href="{{ route('movies.show', $movie['id']) }}">Read more <i class="ion-android-arrow-dropright"></i></a>
        </div>
        <div class="title-in">
            <h6><a href="{{ route('movies.show', $movie['id']) }}">{{ $movie['title'] ?? 'Untitled' }}</a></h6>
            <p>
                <i class="ion-android-star"></i>
                <span>{{ number_format($movie['vote_average'] ?? 0, 1) }}</span> /10
            </p>
        </div>
    </div>
</div>