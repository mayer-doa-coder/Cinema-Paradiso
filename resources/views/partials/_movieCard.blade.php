{{-- Movie Card Partial - Reusable component for displaying movie information --}}
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
            @if(isset($movie['genre_ids']) && !empty($movie['genre_ids']))
                @php
                    $genreColors = ['blue', 'yell', 'green', 'orange', 'red'];
                    $genres = $genres ?? [];
                @endphp
                @foreach(array_slice($movie['genre_ids'], 0, 2) as $index => $genreId)
                    @php
                        $genreName = 'Unknown';
                        foreach($genres as $genre) {
                            if($genre['id'] == $genreId) {
                                $genreName = $genre['name'];
                                break;
                            }
                        }
                        $colorClass = $genreColors[$index % count($genreColors)];
                    @endphp
                    <span class="{{ $colorClass }}">
                        <a href="{{ route('movies.genre', $genreId) }}">{{ $genreName }}</a>
                    </span>
                @endforeach
            @else
                <span class="blue"><a href="#">Movie</a></span>
            @endif
        </div>
        <h6>
            <a href="{{ route('movies.show', $movie['id']) }}">
                {{ $movie['title'] ?? 'Untitled' }}
            </a>
        </h6>
        <p>
            <i class="ion-android-star"></i>
            <span>{{ number_format($movie['vote_average'] ?? 0, 1) }}</span> /10
            @if(isset($movie['release_date']) && $movie['release_date'])
                <span class="year">({{ date('Y', strtotime($movie['release_date'])) }})</span>
            @endif
        </p>
    </div>
</div>