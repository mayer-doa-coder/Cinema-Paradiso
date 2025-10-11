<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ApiRateLimiter
{
    private $maxRequests;
    private $timeWindow;
    private $circuitBreakerThreshold;
    private $circuitBreakerTimeout;

    public function __construct()
    {
        $this->maxRequests = config('services.tmdb.rate_limit', 40); // TMDB allows 40 requests per 10 seconds
        $this->timeWindow = 10; // seconds
        $this->circuitBreakerThreshold = 5; // failures before opening circuit
        $this->circuitBreakerTimeout = 300; // 5 minutes before trying again
    }

    /**
     * Check if we can make an API request
     */
    public function canMakeRequest($endpoint = 'default'): bool
    {
        // Check circuit breaker first
        if ($this->isCircuitOpen($endpoint)) {
            Log::warning("Circuit breaker is open for endpoint: {$endpoint}");
            return false;
        }

        // Check rate limit
        $key = "api_rate_limit:{$endpoint}";
        $currentCount = Cache::get($key, 0);

        if ($currentCount >= $this->maxRequests) {
            Log::warning("Rate limit exceeded for endpoint: {$endpoint}");
            return false;
        }

        return true;
    }

    /**
     * Record an API request
     */
    public function recordRequest($endpoint = 'default'): void
    {
        $key = "api_rate_limit:{$endpoint}";
        $currentCount = Cache::get($key, 0);
        Cache::put($key, $currentCount + 1, $this->timeWindow);
    }

    /**
     * Record a successful API response
     */
    public function recordSuccess($endpoint = 'default'): void
    {
        $this->recordRequest($endpoint);
        $this->resetCircuitBreaker($endpoint);
    }

    /**
     * Record a failed API response
     */
    public function recordFailure($endpoint = 'default'): void
    {
        $failureKey = "api_failures:{$endpoint}";
        $currentFailures = Cache::get($failureKey, 0);
        Cache::put($failureKey, $currentFailures + 1, $this->circuitBreakerTimeout);

        if ($currentFailures + 1 >= $this->circuitBreakerThreshold) {
            $this->openCircuitBreaker($endpoint);
        }
    }

    /**
     * Check if circuit breaker is open
     */
    private function isCircuitOpen($endpoint): bool
    {
        return Cache::has("circuit_breaker:{$endpoint}");
    }

    /**
     * Open circuit breaker
     */
    private function openCircuitBreaker($endpoint): void
    {
        Cache::put("circuit_breaker:{$endpoint}", true, $this->circuitBreakerTimeout);
        Log::error("Circuit breaker opened for endpoint: {$endpoint}");
    }

    /**
     * Reset circuit breaker
     */
    private function resetCircuitBreaker($endpoint): void
    {
        Cache::forget("circuit_breaker:{$endpoint}");
        Cache::forget("api_failures:{$endpoint}");
    }

    /**
     * Get current rate limit status
     */
    public function getRateLimitStatus($endpoint = 'default'): array
    {
        $key = "api_rate_limit:{$endpoint}";
        $currentCount = Cache::get($key, 0);
        $remaining = max(0, $this->maxRequests - $currentCount);
        
        return [
            'endpoint' => $endpoint,
            'requests_made' => $currentCount,
            'requests_remaining' => $remaining,
            'max_requests' => $this->maxRequests,
            'time_window' => $this->timeWindow,
            'circuit_open' => $this->isCircuitOpen($endpoint)
        ];
    }

    /**
     * Wait if necessary before making request
     */
    public function waitIfNeeded($endpoint = 'default'): void
    {
        if (!$this->canMakeRequest($endpoint)) {
            // Calculate wait time based on rate limit reset
            $waitTime = min(5, $this->timeWindow); // Max 5 seconds wait
            Log::info("Rate limited, waiting {$waitTime} seconds for endpoint: {$endpoint}");
            sleep($waitTime);
        }
    }

    /**
     * Make a rate-limited HTTP request
     */
    public function makeRequest($url, $options = [], $endpoint = 'default')
    {
        if (!$this->canMakeRequest($endpoint)) {
            $this->waitIfNeeded($endpoint);
            
            if (!$this->canMakeRequest($endpoint)) {
                throw new \Exception("API rate limit exceeded and circuit breaker is open for: {$endpoint}");
            }
        }

        try {
            $response = Http::timeout(10)->get($url, $options);
            
            if ($response->successful()) {
                $this->recordSuccess($endpoint);
                return $response;
            } else {
                $this->recordFailure($endpoint);
                throw new \Exception("API request failed with status: " . $response->status());
            }
        } catch (\Exception $e) {
            $this->recordFailure($endpoint);
            throw $e;
        }
    }
}