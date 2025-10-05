{{-- TV Show Card Component --}}
<div class="movie-item">
    <div class="mv-img">
        <a href="{{ route('tv.show', $tvShow['id']) }}">
            <img src="{{ $tvShow['poster_url'] ?? asset('images/uploads/movie-placeholder.jpg') }}" 
                 alt="{{ $tvShow['name'] ?? 'TV Show' }}" 
                 width="185" height="284"
                 onerror="this.src='{{ asset('images/uploads/movie-placeholder.jpg') }}';">
        </a>
    </div>
    <div class="hvr-inner">
        <a href="{{ route('tv.show', $tvShow['id']) }}">
            Watch now <i class="ion-android-arrow-dropright"></i>
        </a>
    </div>
    <div class="title-in">
        <h6>
            <a href="{{ route('tv.show', $tvShow['id']) }}">
                {{ Str::limit($tvShow['name'] ?? 'TV Show', 25) }}
            </a>
        </h6>
        <p>
            <i class="ion-android-star"></i>
            <span>{{ number_format($tvShow['vote_average'] ?? 0, 1) }}</span> /10
        </p>
    </div>
</div>