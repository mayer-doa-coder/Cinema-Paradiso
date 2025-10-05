<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NewsService
{
    private $newsApiKey;
    private $guardianApiKey;
    private $cacheDuration = 1800; // 30 minutes

    public function __construct()
    {
        $this->newsApiKey = env('NEWS_API_KEY');
        $this->guardianApiKey = env('GUARDIAN_API_KEY');
    }

    /**
     * Get movie news from multiple sources
     */
    public function getMovieNews($page = 1, $limit = 10)
    {
        $cacheKey = "movie_news_page_{$page}_limit_{$limit}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($page, $limit) {
            $articles = collect();

            // Primary: RSS Feeds (Free and unlimited)
            $rssArticles = $this->parseMovieNewsRSS();
            $articles = $articles->merge($rssArticles);

            // Secondary: NewsAPI (if we have remaining quota)
            if ($this->newsApiKey && $articles->count() < $limit * 2) {
                $newsApiArticles = $this->getNewsAPIContent();
                $articles = $articles->merge($newsApiArticles);
            }

            // Tertiary: Guardian API (if we have key)
            if ($this->guardianApiKey && $articles->count() < $limit * 3) {
                $guardianArticles = $this->getGuardianMovieNews();
                $articles = $articles->merge($guardianArticles);
            }

            // Sort by publication date and paginate
            $sortedArticles = $articles->sortByDesc('published_at')->unique('url');
            
            $offset = ($page - 1) * $limit;
            $paginatedArticles = $sortedArticles->slice($offset, $limit)->values();

            return [
                'articles' => $paginatedArticles,
                'total' => $sortedArticles->count(),
                'current_page' => $page,
                'per_page' => $limit,
                'last_page' => ceil($sortedArticles->count() / $limit)
            ];
        });
    }

    /**
     * Parse RSS feeds from major entertainment sites
     */
    private function parseMovieNewsRSS()
    {
        $feeds = [
            'variety' => [
                'url' => 'https://variety.com/c/film/feed/',
                'name' => 'Variety'
            ],
            'thr' => [
                'url' => 'https://www.hollywoodreporter.com/c/movies/feed/',
                'name' => 'The Hollywood Reporter'
            ],
            'ew' => [
                'url' => 'https://ew.com/movies/feed/',
                'name' => 'Entertainment Weekly'
            ],
            'deadline' => [
                'url' => 'https://deadline.com/category/movie-news/feed/',
                'name' => 'Deadline'
            ],
            'screenrant' => [
                'url' => 'https://screenrant.com/movie-news/feed/',
                'name' => 'Screen Rant'
            ]
        ];

        $articles = collect();

        foreach ($feeds as $sourceKey => $source) {
            try {
                $response = Http::timeout(10)->get($source['url']);
                
                if ($response->successful()) {
                    $rss = simplexml_load_string($response->body());
                    
                    if ($rss && isset($rss->channel->item)) {
                        foreach ($rss->channel->item as $item) {
                            $articles->push([
                                'title' => $this->cleanTitle((string) $item->title),
                                'description' => $this->cleanDescription((string) $item->description),
                                'url' => (string) $item->link,
                                'published_at' => $this->parseDate((string) $item->pubDate),
                                'source' => $source['name'],
                                'source_key' => $sourceKey,
                                'image' => $this->extractImageFromRSS($item),
                                'author' => $this->extractAuthor($item),
                                'category' => 'movie-news',
                                'type' => 'rss'
                            ]);
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error("Failed to parse RSS from {$sourceKey}: " . $e->getMessage());
            }
        }

        return $articles;
    }

    /**
     * Get news from NewsAPI
     */
    private function getNewsAPIContent()
    {
        if (!$this->newsApiKey) {
            return collect();
        }

        try {
            $response = Http::get('https://newsapi.org/v2/everything', [
                'q' => 'movie OR cinema OR film OR entertainment OR hollywood',
                'sortBy' => 'publishedAt',
                'language' => 'en',
                'pageSize' => 20,
                'domains' => 'variety.com,hollywoodreporter.com,ew.com,deadline.com',
                'apiKey' => $this->newsApiKey
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                return collect($data['articles'])->map(function ($article) {
                    return [
                        'title' => $article['title'],
                        'description' => $article['description'],
                        'url' => $article['url'],
                        'published_at' => Carbon::parse($article['publishedAt'])->format('Y-m-d H:i:s'),
                        'source' => $article['source']['name'] ?? 'NewsAPI',
                        'source_key' => 'newsapi',
                        'image' => $article['urlToImage'],
                        'author' => $article['author'],
                        'category' => 'movie-news',
                        'type' => 'api'
                    ];
                });
            }
        } catch (\Exception $e) {
            Log::error('NewsAPI request failed: ' . $e->getMessage());
        }

        return collect();
    }

    /**
     * Get news from Guardian API
     */
    private function getGuardianMovieNews()
    {
        if (!$this->guardianApiKey) {
            return collect();
        }

        try {
            $response = Http::get('https://content.guardianapis.com/search', [
                'section' => 'film',
                'show-fields' => 'headline,thumbnail,short-url,body,byline',
                'page-size' => 20,
                'order-by' => 'newest',
                'api-key' => $this->guardianApiKey
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                return collect($data['response']['results'])->map(function ($article) {
                    return [
                        'title' => $article['webTitle'],
                        'description' => $this->extractDescriptionFromBody($article['fields']['body'] ?? ''),
                        'url' => $article['webUrl'],
                        'published_at' => Carbon::parse($article['webPublicationDate'])->format('Y-m-d H:i:s'),
                        'source' => 'The Guardian',
                        'source_key' => 'guardian',
                        'image' => $article['fields']['thumbnail'] ?? null,
                        'author' => $article['fields']['byline'] ?? 'The Guardian',
                        'category' => 'movie-news',
                        'type' => 'api'
                    ];
                });
            }
        } catch (\Exception $e) {
            Log::error('Guardian API request failed: ' . $e->getMessage());
        }

        return collect();
    }

    /**
     * Get Reddit movie discussions
     */
    public function getRedditMovieDiscussions($limit = 10)
    {
        $cacheKey = "reddit_movie_discussions_{$limit}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($limit) {
            $subreddits = ['movies', 'entertainment', 'boxoffice', 'MovieDetails'];
            $discussions = collect();

            foreach ($subreddits as $subreddit) {
                try {
                    $response = Http::get("https://www.reddit.com/r/{$subreddit}/hot.json", [
                        'limit' => 10
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        
                        foreach ($data['data']['children'] as $post) {
                            $postData = $post['data'];
                            
                            // Filter for movie-related content
                            if ($this->isMovieRelated($postData['title'])) {
                                $discussions->push([
                                    'title' => $postData['title'],
                                    'url' => 'https://reddit.com' . $postData['permalink'],
                                    'score' => $postData['score'],
                                    'comments' => $postData['num_comments'],
                                    'author' => $postData['author'],
                                    'subreddit' => $postData['subreddit'],
                                    'created' => Carbon::createFromTimestamp($postData['created_utc'])->format('Y-m-d H:i:s'),
                                    'thumbnail' => $postData['thumbnail'] !== 'self' ? $postData['thumbnail'] : null,
                                    'type' => 'discussion'
                                ]);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to fetch Reddit data from r/{$subreddit}: " . $e->getMessage());
                }
            }

            return $discussions->sortByDesc('score')->take($limit);
        });
    }

    /**
     * Helper methods
     */
    private function cleanTitle($title)
    {
        return html_entity_decode(strip_tags(trim($title)));
    }

    private function cleanDescription($description)
    {
        $cleaned = html_entity_decode(strip_tags($description));
        return strlen($cleaned) > 200 ? substr($cleaned, 0, 200) . '...' : $cleaned;
    }

    private function parseDate($dateString)
    {
        try {
            return Carbon::parse($dateString)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return now()->format('Y-m-d H:i:s');
        }
    }

    private function extractImageFromRSS($item)
    {
        // Try to find image in description or content:encoded
        $description = (string) $item->description;
        $content = isset($item->children('content', true)->encoded) ? 
                   (string) $item->children('content', true)->encoded : '';
        
        $text = $description . ' ' . $content;
        
        if (preg_match('/<img[^>]+src="([^"]+)"/', $text, $matches)) {
            return $matches[1];
        }
        
        // Try media:content or media:thumbnail
        if (isset($item->children('media', true)->content)) {
            return (string) $item->children('media', true)->content->attributes()->url;
        }
        
        if (isset($item->children('media', true)->thumbnail)) {
            return (string) $item->children('media', true)->thumbnail->attributes()->url;
        }
        
        return null;
    }

    private function extractAuthor($item)
    {
        if (isset($item->children('dc', true)->creator)) {
            return (string) $item->children('dc', true)->creator;
        }
        
        return (string) ($item->author ?? 'Unknown');
    }

    private function extractDescriptionFromBody($body)
    {
        $text = strip_tags($body);
        return strlen($text) > 200 ? substr($text, 0, 200) . '...' : $text;
    }

    private function isMovieRelated($title)
    {
        $keywords = ['movie', 'film', 'cinema', 'trailer', 'box office', 'director', 'actor', 'actress', 'hollywood', 'netflix', 'disney', 'marvel', 'dc'];
        $title = strtolower($title);
        
        foreach ($keywords as $keyword) {
            if (strpos($title, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Clear all news cache
     */
    public function clearCache()
    {
        $keys = [
            'movie_news_page_*',
            'reddit_movie_discussions_*'
        ];
        
        foreach ($keys as $pattern) {
            Cache::forget($pattern);
        }
    }
}