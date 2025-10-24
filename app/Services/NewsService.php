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
    private $cacheDuration = 21600; // 6 hours (as requested)

    public function __construct()
    {
        $this->newsApiKey = env('NEWS_API_KEY');
        $this->guardianApiKey = env('GUARDIAN_API_KEY');
    }

    /**
     * Get movie news from multiple sources
     */
    /**
     * Get the latest entertainment content from ALL available sources with images
     * Prioritizes recency and ensures all results have valid images
     */
    public function getLatestContentWithImages($page = 1, $limit = 10)
    {
        $cacheKey = "latest_content_with_images_page_{$page}_limit_{$limit}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($page, $limit) {
            $allContent = collect();

            // 1. RSS Feeds (Primary source - most reliable for images)
            $rssArticles = $this->parseMovieNewsRSS();
            $allContent = $allContent->merge($rssArticles);

            // 2. NewsAPI (High quality, good images)
            if ($this->newsApiKey) {
                $newsApiArticles = $this->getNewsAPIContent();
                $allContent = $allContent->merge($newsApiArticles);
            }

            // 3. Guardian API (Quality journalism with images)
            if ($this->guardianApiKey) {
                $guardianArticles = $this->getGuardianMovieNews();
                $allContent = $allContent->merge($guardianArticles);
            }

            // 4. Reddit Discussions (Community content with thumbnails)
            $redditDiscussions = $this->getRedditMovieDiscussions(20);
            // Convert Reddit format to match our article structure
            $redditArticles = $redditDiscussions->map(function ($discussion) {
                return [
                    'title' => $discussion['title'],
                    'description' => "Reddit discussion from r/{$discussion['subreddit']} with {$discussion['comments']} comments",
                    'url' => $discussion['url'],
                    'published_at' => $discussion['created'],
                    'source' => "Reddit - r/{$discussion['subreddit']}",
                    'source_key' => 'reddit',
                    'image' => $discussion['thumbnail'] && $discussion['thumbnail'] !== 'self' && $discussion['thumbnail'] !== 'default' ? $discussion['thumbnail'] : null,
                    'author' => $discussion['author'],
                    'category' => 'discussion',
                    'type' => 'reddit',
                    'score' => $discussion['score']
                ];
            });
            $allContent = $allContent->merge($redditArticles);

            // Filter for articles with valid images only
            $contentWithImages = $allContent->filter(function ($article) {
                return isset($article['image']) && 
                       !empty($article['image']) && 
                       $article['image'] !== null &&
                       $article['image'] !== 'self' &&
                       $article['image'] !== 'default' &&
                       $article['image'] !== 'nsfw' &&
                       filter_var($article['image'], FILTER_VALIDATE_URL) &&
                       !str_contains(strtolower($article['image']), 'placeholder') &&
                       !str_contains(strtolower($article['image']), 'thumbs.redditmedia.com'); // Filter out low-quality Reddit thumbs
            });

            // Sort by publication date (most recent first) and remove duplicates
            $sortedContent = $contentWithImages
                ->sortByDesc(function ($article) {
                    // Use Carbon to ensure proper date sorting
                    try {
                        return Carbon::parse($article['published_at'])->timestamp;
                    } catch (\Exception $e) {
                        return 0; // Put unparseable dates at the end
                    }
                })
                ->unique('url')
                ->values();

            // Paginate results
            $offset = ($page - 1) * $limit;
            $paginatedContent = $sortedContent->slice($offset, $limit)->values();

            return [
                'articles' => $paginatedContent,
                'total' => $sortedContent->count(),
                'current_page' => $page,
                'per_page' => $limit,
                'last_page' => ceil($sortedContent->count() / $limit),
                'sources_used' => $allContent->pluck('source_key')->unique()->values(),
                'total_before_filter' => $allContent->count(),
                'with_images' => $contentWithImages->count()
            ];
        });
    }

    /**
     * Get movie news articles that have valid images
     */
    public function getMovieNewsWithImages($page = 1, $limit = 10)
    {
        $cacheKey = "movie_news_with_images_page_{$page}_limit_{$limit}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($page, $limit) {
            // Fetch more articles than needed to ensure we get enough with images
            $fetchLimit = $limit * 5; // Fetch 5x more to filter
            $allArticles = collect();

            // Primary: RSS Feeds (Free and unlimited)
            $rssArticles = $this->parseMovieNewsRSS();
            $allArticles = $allArticles->merge($rssArticles);

            // Secondary: NewsAPI (if we have remaining quota)
            if ($this->newsApiKey) {
                $newsApiArticles = $this->getNewsAPIContent();
                $allArticles = $allArticles->merge($newsApiArticles);
            }

            // Tertiary: Guardian API (if we have key)
            if ($this->guardianApiKey) {
                $guardianArticles = $this->getGuardianMovieNews();
                $allArticles = $allArticles->merge($guardianArticles);
            }

            // Filter articles to only include those with valid images
            $articlesWithImages = $allArticles->filter(function ($article) {
                return isset($article['image']) && 
                       !empty($article['image']) && 
                       $article['image'] !== null &&
                       filter_var($article['image'], FILTER_VALIDATE_URL) &&
                       !str_contains(strtolower($article['image']), 'placeholder');
            });

            // Sort by publication date and remove duplicates
            $sortedArticles = $articlesWithImages->sortByDesc('published_at')->unique('url');
            
            // Paginate
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

    public function getMovieNews($page = 1, $limit = 10)
    {
        $cacheKey = "movie_news_page_{$page}_limit_{$limit}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($page, $limit) {
            $articles = collect();

            // Primary: RSS Feeds (Free and unlimited) - fetch more to ensure enough with images
            $rssArticles = $this->parseMovieNewsRSS();
            $articles = $articles->merge($rssArticles);

            // Filter articles to only include those with valid images FIRST
            $articlesWithImages = $articles->filter(function ($article) {
                return $this->isValidImageUrl($article['image'] ?? null);
            });

            Log::info("RSS Articles: " . $articles->count() . ", With Images: " . $articlesWithImages->count());

            // If we don't have enough, try NewsAPI and Guardian
            if ($articlesWithImages->count() < $limit * 2) {
                if ($this->newsApiKey) {
                    $newsApiArticles = $this->getNewsAPIContent();
                    $newsApiWithImages = $newsApiArticles->filter(function ($article) {
                        return $this->isValidImageUrl($article['image'] ?? null);
                    });
                    $articlesWithImages = $articlesWithImages->merge($newsApiWithImages);
                }
            }

            if ($articlesWithImages->count() < $limit * 2) {
                if ($this->guardianApiKey) {
                    $guardianArticles = $this->getGuardianMovieNews();
                    $guardianWithImages = $guardianArticles->filter(function ($article) {
                        return $this->isValidImageUrl($article['image'] ?? null);
                    });
                    $articlesWithImages = $articlesWithImages->merge($guardianWithImages);
                }
            }

            // Sort by publication date and remove duplicates
            $sortedArticles = $articlesWithImages
                ->sortByDesc('published_at')
                ->unique('url')
                ->values();
            
            // Paginate
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
        // Only use feeds that consistently have good quality images
        $feeds = [
            'variety' => [
                'url' => 'https://variety.com/c/film/feed/',
                'name' => 'Variety'
            ],
            'thr' => [
                'url' => 'https://www.hollywoodreporter.com/c/movies/feed/',
                'name' => 'Hollywood Reporter'
            ],
            'deadline' => [
                'url' => 'https://deadline.com/category/movie-news/feed/',
                'name' => 'Deadline'
            ],
            'screenrant' => [
                'url' => 'https://screenrant.com/movie-news/feed/',
                'name' => 'Screen Rant'
            ],
            'collider' => [
                'url' => 'https://collider.com/rss/',
                'name' => 'Collider'
            ]
        ];

        $articles = collect();

        foreach ($feeds as $sourceKey => $source) {
            try {
                $response = Http::timeout(8)->get($source['url']);
                
                if ($response->successful()) {
                    $rss = simplexml_load_string($response->body());
                    
                    if ($rss && isset($rss->channel->item)) {
                        // Limit to 15 items per feed to speed up processing
                        $itemCount = 0;
                        
                        foreach ($rss->channel->item as $item) {
                            if ($itemCount >= 15) break;
                            
                            $imageUrl = $this->extractImageFromRSS($item);
                            
                            // Only add articles with valid images
                            if ($imageUrl && filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                                $articles->push([
                                    'title' => $this->cleanTitle((string) $item->title),
                                    'description' => $this->cleanDescription((string) $item->description),
                                    'url' => (string) $item->link,
                                    'published_at' => $this->parseDate((string) $item->pubDate),
                                    'source' => $source['name'],
                                    'source_key' => $sourceKey,
                                    'image' => $imageUrl,
                                    'author' => $this->extractAuthor($item),
                                    'category' => 'movie-news',
                                    'type' => 'rss'
                                ]);
                                $itemCount++;
                            }
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
                'pageSize' => 50,
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
                'page-size' => 50,
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
        // Try multiple methods to extract image URLs from RSS feeds
        
        // 1. Try enclosure tag (common in RSS feeds)
        if (isset($item->enclosure) && $item->enclosure->attributes()) {
            $type = (string) $item->enclosure->attributes()->type;
            if (str_starts_with($type, 'image/')) {
                return (string) $item->enclosure->attributes()->url;
            }
        }
        
        // 2. Try media:content or media:thumbnail (MediaRSS namespace)
        if (isset($item->children('media', true)->content)) {
            $mediaContent = $item->children('media', true)->content;
            if ($mediaContent->attributes()) {
                $type = (string) $mediaContent->attributes()->type;
                if (str_starts_with($type, 'image/') || empty($type)) {
                    return (string) $mediaContent->attributes()->url;
                }
            }
        }
        
        if (isset($item->children('media', true)->thumbnail)) {
            return (string) $item->children('media', true)->thumbnail->attributes()->url;
        }
        
        // 3. Try to find image in description or content:encoded
        $description = (string) $item->description;
        $content = isset($item->children('content', true)->encoded) ? 
                   (string) $item->children('content', true)->encoded : '';
        
        $text = $description . ' ' . $content;
        
        // Look for various image patterns
        $patterns = [
            '/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/',  // Standard img tags
            '/<img[^>]+data-src=["\']([^"\']+)["\'][^>]*>/', // Lazy loaded images
            '/https?:\/\/[^\s<>"]+\.(?:jpg|jpeg|png|gif|webp)(?:\?[^\s<>"]*)?/i', // Direct image URLs
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $imageUrl = $matches[1] ?? $matches[0];
                // Validate it's a proper image URL
                if (filter_var($imageUrl, FILTER_VALIDATE_URL) && 
                    preg_match('/\.(jpg|jpeg|png|gif|webp)(\?.*)?$/i', $imageUrl)) {
                    return $imageUrl;
                }
            }
        }
        
        // 4. Try RSS 2.0 image tag
        if (isset($item->image)) {
            return (string) $item->image;
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
     * Validate if image URL is accessible and valid
     */
    private function isValidImageUrl($url)
    {
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }
        
        // Quick validation - check URL pattern
        $invalidPatterns = [
            'placeholder',
            'default',
            'thumbs.redditmedia.com',
            'data:image',
            'b.thumbs.redditmedia.com'
        ];
        
        foreach ($invalidPatterns as $pattern) {
            if (stripos($url, $pattern) !== false) {
                return false;
            }
        }
        
        // Check if it has an image extension
        if (!preg_match('/\.(jpg|jpeg|png|gif|webp)(\?.*)?$/i', $url)) {
            return false;
        }
        
        return true;
    }

    /**
     * Clear all news cache
     */
    public function clearCache()
    {
        $patterns = [
            'movie_news_page_',
            'reddit_movie_discussions_',
            'latest_content_with_images_page_',
            'movie_news_with_images_page_'
        ];
        
        // Clear all cached news data
        foreach ($patterns as $pattern) {
            for ($i = 1; $i <= 10; $i++) {
                for ($j = 10; $j <= 50; $j += 10) {
                    Cache::forget("{$pattern}{$i}_limit_{$j}");
                }
            }
        }
        
        return true;
    }
}