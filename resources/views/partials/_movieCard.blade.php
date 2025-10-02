{{-- Movie Card Partial - Clean poster only --}}
<div class="movie-item">
    <div class="mv-img">
        <a href="{{ route('movies.show', $movie['id']) }}">
            <img src="{{ app('App\Services\MovieService')->getImageUrl($movie['poster_path'] ?? null, 'w342') }}" 
                 alt="{{ $movie['title'] ?? 'Movie Poster' }}" 
                 width="285" 
                 height="437">
        </a>
    </div>
</div>