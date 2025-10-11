<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | TMDb (The Movie Database) Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for The Movie Database API integration.
    | Get your API key from: https://www.themoviedb.org/settings/api
    |
    */
    'tmdb' => [
        'api_key' => env('TMDB_API_KEY', '0aa37805e4fe53b8ef1c48e133acdce6'),
        'base_url' => env('TMDB_BASE_URL', 'https://api.themoviedb.org/3'),
        'image_base_url' => env('TMDB_IMAGE_BASE_URL', 'https://image.tmdb.org/t/p'),
        'cache_duration' => 3600, // Cache for 1 hour (default)
        'cache_durations' => [
            'popular' => 21600,    // 6 hours - popular data changes slowly
            'top_rated' => 43200,  // 12 hours - top rated rarely changes
            'genres' => 86400,     // 24 hours - genres rarely change
            'upcoming' => 7200,    // 2 hours - upcoming changes more frequently
            'trending' => 3600,    // 1 hour - trending changes frequently
            'search' => 1800,      // 30 minutes - search results can be shorter
            'person' => 21600,     // 6 hours - celebrity data changes slowly
            'movie_details' => 43200, // 12 hours - movie details rarely change
        ],
        'per_page' => 20, // Default results per page
        'rate_limit' => 40, // TMDB allows 40 requests per 10 seconds
        'timeout' => 10, // Request timeout in seconds
    ],

];
