# Cinema Paradiso

A comprehensive movie and television database web application built with Laravel and modern web technologies. Cinema Paradiso provides users with an extensive platform to discover, track, and discuss movies and TV shows while connecting with a community of film enthusiasts.

## Overview

Cinema Paradiso is a full-featured entertainment platform that integrates with The Movie Database (TMDb) API to provide users with access to a vast collection of movies, TV shows, and celebrity information. The application combines content discovery with social features, enabling users to build their personal collections, engage with other members, and stay updated with the latest entertainment news.

## Features

### Content Discovery
- **Movie Database**: Browse and search through an extensive catalog of movies with detailed information including cast, crew, ratings, and reviews
- **TV Show Database**: Access comprehensive TV series information with season and episode details
- **Celebrity Profiles**: View detailed information about actors, directors, and other entertainment industry professionals
- **Advanced Search**: Universal search functionality across movies, TV shows, and celebrities
- **Genre Filtering**: Browse content by specific genres
- **Rich Media**: Access trailers, images, and promotional content for movies and TV shows

### User Collections
- **Personal Movie Collection**: Add and organize movies in your personal library
- **Watchlist**: Maintain a list of movies and TV shows to watch later
- **Reviews and Ratings**: Write and publish reviews for movies and TV shows
- **Favorites**: Mark content as favorites for quick access
- **User Statistics**: Track viewing habits and collection statistics

### Social Features
- **User Profiles**: Customizable user profiles with avatars and biographical information
- **Follow System**: Follow other users to stay updated with their activity
- **Messaging**: Direct messaging system with chat requests for privacy control
- **Community Discovery**: Search and connect with other members based on shared interests
- **User Activity Tracking**: View follower and following lists

### News and Updates
- **Entertainment News**: Aggregated news from multiple sources including NewsAPI, The Guardian, and RSS feeds
- **Reddit Integration**: Access popular movie discussions from Reddit
- **Content Caching**: Optimized news delivery with intelligent caching mechanisms

### Technical Features
- **Responsive Design**: Built with Tailwind CSS for a modern, mobile-friendly interface
- **API Integration**: Seamless integration with TMDb API for real-time content data
- **Rate Limiting**: Protection against API abuse with configurable rate limits
- **Caching System**: Comprehensive caching strategy for improved performance
- **Authentication**: Secure user authentication and authorization system
- **Background Jobs**: Queue system for handling time-intensive operations

## Technology Stack

### Backend
- **Framework**: Laravel 12.0
- **Language**: PHP 8.2+
- **Database**: MySQL/PostgreSQL (configurable)
- **Caching**: Redis/File-based caching
- **Queue System**: Laravel Queue with database/Redis driver
- **Email**: Laravel Mail with multiple driver support

### Frontend
- **CSS Framework**: Tailwind CSS 4.0
- **Build Tool**: Vite 7.0
- **HTTP Client**: Axios
- **JavaScript**: Modern ES6+ standards

### External APIs
- **TMDb API**: The Movie Database API for movie and TV show data
- **NewsAPI**: Entertainment news aggregation
- **The Guardian API**: Quality journalism and reviews
- **Reddit API**: Community discussions and trending topics

## Project Structure

```
cinema-paradiso/
├── app/
│   ├── Console/
│   │   └── Commands/              # Custom Artisan commands
│   ├── Http/
│   │   ├── Controllers/           # Application controllers
│   │   │   ├── AuthController.php
│   │   │   ├── MovieController.php
│   │   │   ├── TVShowController.php
│   │   │   ├── CelebrityController.php
│   │   │   ├── CommunityController.php
│   │   │   ├── ChatController.php
│   │   │   ├── UserController.php
│   │   │   ├── UserMovieController.php
│   │   │   ├── UserTVShowController.php
│   │   │   ├── BlogController.php
│   │   │   └── ContactController.php
│   │   └── Middleware/            # Custom middleware
│   ├── Jobs/
│   │   └── WarmCacheJob.php       # Queue jobs
│   ├── Mail/
│   │   ├── ContactNotification.php
│   │   └── ForgotPasswordMail.php
│   ├── Models/                    # Eloquent models
│   │   ├── User.php
│   │   ├── Movie.php
│   │   ├── ChatMessage.php
│   │   ├── ChatRequest.php
│   │   ├── Contact.php
│   │   ├── UserActivity.php
│   │   ├── UserFavoriteMovie.php
│   │   ├── UserFollower.php
│   │   ├── UserMovie.php
│   │   ├── UserMovieLike.php
│   │   ├── UserMovieReview.php
│   │   └── UserWatchlist.php
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   └── Services/                  # Business logic services
│       ├── ApiRateLimiter.php
│       ├── CacheOptimizationService.php
│       ├── MovieService.php
│       ├── NewsService.php
│       ├── TVShowService.php
│       └── UserPopularityService.php
├── bootstrap/
│   ├── app.php                    # Application bootstrap
│   ├── providers.php
│   └── cache/                     # Bootstrap cache
├── config/                        # Configuration files
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   ├── database.php
│   ├── mail.php
│   ├── queue.php
│   ├── services.php               # Third-party API configuration
│   └── session.php
├── database/
│   ├── factories/
│   │   └── UserFactory.php
│   ├── migrations/                # Database migrations
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2025_09_30_190326_create_contacts_table.php
│   │   ├── 2025_10_03_213727_add_community_features_to_users_table.php
│   │   ├── 2025_10_20_173048_create_user_movies_table.php
│   │   ├── 2025_10_20_173153_create_user_movie_likes_table.php
│   │   ├── 2025_10_20_173636_create_user_watchlist_table.php
│   │   ├── 2025_10_20_181236_create_user_movie_reviews_table.php
│   │   ├── 2025_10_23_202109_create_chat_messages_table.php
│   │   └── 2025_10_23_202206_create_chat_requests_table.php
│   └── seeders/                   # Database seeders
├── public/                        # Public assets
│   ├── index.php                  # Application entry point
│   ├── css/                       # Compiled CSS
│   ├── js/                        # Compiled JavaScript
│   ├── images/                    # Static images
│   └── storage/                   # Public storage link
├── resources/
│   ├── css/                       # Source CSS files
│   ├── fonts/                     # Custom fonts
│   ├── images/                    # Source images
│   └── views/                     # Blade templates
├── routes/
│   ├── web.php                    # Web routes
│   └── console.php                # Console routes
├── storage/
│   ├── app/                       # Application storage
│   ├── framework/                 # Framework storage
│   └── logs/                      # Application logs
├── tests/
│   ├── Feature/                   # Feature tests
│   └── Unit/                      # Unit tests
├── vendor/                        # Composer dependencies
├── .env                          # Environment configuration
├── artisan                       # Artisan CLI
├── composer.json                 # PHP dependencies
├── package.json                  # Node dependencies
├── phpunit.xml                   # PHPUnit configuration
├── vite.config.js                # Vite configuration
└── README.md                     # Project documentation
```

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL or PostgreSQL database
- Redis (recommended for caching and queues)

### Setup Instructions

1. Clone the repository:
```bash
git clone https://github.com/yourusername/cinema-paradiso.git
cd cinema-paradiso
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install JavaScript dependencies:
```bash
npm install
```

4. Create and configure environment file:
```bash
cp .env.example .env
```

5. Configure your `.env` file with the following essential settings:
```env
APP_NAME="Cinema Paradiso"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cinema_paradiso
DB_USERNAME=your_username
DB_PASSWORD=your_password

TMDB_API_KEY=your_tmdb_api_key
NEWS_API_KEY=your_news_api_key
GUARDIAN_API_KEY=your_guardian_api_key

MAIL_MAILER=smtp
MAIL_HOST=your_mail_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
```

6. Generate application key:
```bash
php artisan key:generate
```

7. Run database migrations:
```bash
php artisan migrate
```

8. Build frontend assets:
```bash
npm run build
```

9. Start the development server:
```bash
php artisan serve
```

10. In a separate terminal, compile assets for development:
```bash
npm run dev
```

### Queue Worker (Optional but Recommended)

For background job processing:
```bash
php artisan queue:work
```

## Configuration

### TMDb API Setup
1. Register for a free account at [The Movie Database](https://www.themoviedb.org/)
2. Navigate to Settings > API and request an API key
3. Add your API key to the `.env` file as `TMDB_API_KEY`

### News API Setup
1. Register at [NewsAPI](https://newsapi.org/)
2. Obtain your API key
3. Add to `.env` as `NEWS_API_KEY`

### The Guardian API Setup
1. Register at [The Guardian Open Platform](https://open-platform.theguardian.com/)
2. Request an API key
3. Add to `.env` as `GUARDIAN_API_KEY`

## Database Schema

The application includes the following primary tables:

- `users`: User accounts and authentication
- `user_movies`: User movie collections
- `user_movie_likes`: Movie likes/favorites
- `user_movie_reviews`: User-submitted movie reviews
- `user_watchlist`: Movies and TV shows on user watchlists
- `chat_messages`: Direct messages between users
- `chat_requests`: Chat request management
- `user_followers`: User follow relationships
- `contacts`: Contact form submissions
- `user_activities`: User activity tracking

## Security Features

- CSRF protection on all forms
- Rate limiting on sensitive endpoints (avatar uploads, messaging, chat requests)
- Password hashing with bcrypt
- SQL injection protection through Eloquent ORM
- XSS protection through Laravel's built-in escaping
- Authentication middleware for protected routes

## Performance Optimization

- **Multi-layer Caching**: Strategic caching of API responses, database queries, and rendered views
- **Image Optimization**: Lazy loading and responsive images
- **Database Indexing**: Optimized queries with proper indexing
- **API Rate Limiting**: Prevents excessive API calls with intelligent rate limiting
- **Queue System**: Offloads time-intensive operations to background jobs
- **Asset Bundling**: Vite-powered asset compilation and minification

## API Endpoints

### Public Endpoints
- `GET /` - Home page
- `GET /movies` - Browse movies
- `GET /movies/{id}` - Movie details
- `GET /tv` - Browse TV shows
- `GET /tv/{id}` - TV show details
- `GET /celebrities` - Browse celebrities
- `GET /blog` - Entertainment news
- `GET /community` - Community member directory

### Protected Endpoints (Authentication Required)
- `POST /movies/add` - Add movie to collection
- `POST /movies/like` - Like/unlike a movie
- `POST /movies/watchlist` - Add/remove from watchlist
- `POST /movies/review` - Submit movie review
- `GET /messages` - View messages
- `POST /messages/{receiver}/send` - Send message
- `POST /profile/follow/{userId}` - Follow user
- `POST /profile/update` - Update profile

## Testing

Run the test suite:
```bash
php artisan test
```

Run specific test suites:
```bash
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

## Contributing

Contributions are welcome. Please follow these guidelines:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/your-feature-name`)
3. Commit your changes (`git commit -m 'Add comprehensive description'`)
4. Push to the branch (`git push origin feature/your-feature-name`)
5. Open a Pull Request

Please ensure your code follows PSR-12 coding standards and includes appropriate tests.

## Code Style

This project follows PSR-12 coding standards. Format your code using Laravel Pint:
```bash
./vendor/bin/pint
```

## License

This project is licensed under the MIT License. See the LICENSE file for details.

## Acknowledgments

- [The Movie Database (TMDb)](https://www.themoviedb.org/) for providing comprehensive movie and TV show data
- [NewsAPI](https://newsapi.org/) for entertainment news aggregation
- [The Guardian](https://www.theguardian.com/) for quality journalism
- Laravel community for the excellent framework and ecosystem

## Support

For issues, questions, or contributions, please open an issue on the GitHub repository.

## Project Status

Active development. New features and improvements are regularly added.

---

Built with Laravel and Tailwind CSS
