{{-- Small Movie Card Partial - Full featured card for carousels/tabs --}}
<div class="slide-it">
    <div class="movie-item">
        <div class="mv-img">
            <a href="{{ route('movies.show', $movie['id']) }}">
                <img src="{{ app('App\Services\MovieService')->getImageUrl($movie['poster_path'] ?? null, 'w300') }}" 
                     alt="{{ $movie['title'] ?? 'Movie Poster' }}" 
                     width="185" 
                     height="284">
            </a>
        </div>
    </div>
</div>