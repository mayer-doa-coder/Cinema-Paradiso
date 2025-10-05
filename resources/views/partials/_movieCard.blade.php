{{-- Movie Card Partial - Full featured card for sliders --}}
<div class="movie-item">
    <div class="mv-img">
        <a href="{{ route('movies.show', $movie['id']) }}">
            <img src="{{ app('App\Services\MovieService')->getImageUrl($movie['poster_path'] ?? null, 'w342') }}" 
                 alt="{{ $movie['title'] ?? 'Movie Poster' }}" 
                 width="285" 
                 height="437">
        </a>
    </div>
    <div class="title-in">
        <div class="cate">
            @if(isset($genres) && isset($movie['genre_ids']) && !empty($movie['genre_ids']))
                @php
                    $movieGenre = collect($genres)->firstWhere('id', $movie['genre_ids'][0]);
                @endphp
                @if($movieGenre)
                    <span class="blue"><a href="{{ route('movies.genre', $movieGenre['id']) }}">{{ $movieGenre['name'] }}</a></span>
                @endif
            @endif
        </div>
        <h6><a href="{{ route('movies.show', $movie['id']) }}">{{ $movie['title'] ?? 'Untitled' }}</a></h6>
        <p><i class="ion-android-star"></i><span>{{ number_format($movie['vote_average'] ?? 0, 1) }}</span> /10</p>
    </div>
</div>