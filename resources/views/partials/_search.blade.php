<!-- Universal Top Search Form -->
<div class="top-search">
    <div class="search-dropdown">
        <i class="ion-ios-list-outline"></i>
        <select id="search-type">
            <option value="movies" {{ request()->is('movies*') || request()->is('/') || (!request()->is('tv*')) ? 'selected' : '' }}>Movies</option>
            <option value="tvshows" {{ request()->is('tv*') ? 'selected' : '' }}>TV Shows</option>
        </select>
    </div>
    <div class="search-input">
        <input type="text" id="search-query" placeholder="Search for movies or TV shows" 
               value="{{ request('q', '') }}">
        <i class="ion-ios-search" id="search-icon" style="cursor: pointer;"></i>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchIcon = document.getElementById('search-icon');
    const searchQuery = document.getElementById('search-query');
    const searchType = document.getElementById('search-type');
    
    // Function to perform search
    function performSearch() {
        const query = searchQuery.value.trim();
        const type = searchType.value;
        
        if (!query) {
            alert('Please enter a search query');
            return;
        }
        
        // Use universal search route that will redirect to appropriate search page
        const searchUrl = '/universal-search?q=' + encodeURIComponent(query) + '&type=' + encodeURIComponent(type);
        
        // Navigate to search results
        window.location.href = searchUrl;
    }
    
    // Search on icon click
    if (searchIcon) {
        searchIcon.addEventListener('click', performSearch);
    }
    
    // Search on Enter key press
    if (searchQuery) {
        searchQuery.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    }
    
    // Auto-select search type based on current page
    if (searchType) {
        const currentPath = window.location.pathname;
        if (currentPath.includes('/tv')) {
            searchType.value = 'tvshows';
        } else {
            searchType.value = 'movies';
        }
        
        // Update placeholder text based on selected type
        searchType.addEventListener('change', function() {
            if (searchQuery) {
                if (this.value === 'tvshows') {
                    searchQuery.placeholder = 'Search for TV shows';
                } else {
                    searchQuery.placeholder = 'Search for movies';
                }
            }
        });
        
        // Set initial placeholder
        if (searchQuery) {
            if (searchType.value === 'tvshows') {
                searchQuery.placeholder = 'Search for TV shows';
            } else {
                searchQuery.placeholder = 'Search for movies';
            }
        }
    }
});
</script>