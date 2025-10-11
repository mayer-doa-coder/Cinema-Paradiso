<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompressResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only compress if the client accepts gzip and the response is compressible
        if ($this->shouldCompress($request, $response)) {
            $content = $response->getContent();
            
            if ($content && function_exists('gzencode')) {
                $compressed = gzencode($content, 6); // Compression level 6 (balanced)
                
                if ($compressed !== false && strlen($compressed) < strlen($content)) {
                    $response->setContent($compressed);
                    $response->headers->set('Content-Encoding', 'gzip');
                    $response->headers->set('Content-Length', strlen($compressed));
                    $response->headers->set('Vary', 'Accept-Encoding');
                }
            }
        }

        // Add cache headers for static-like content
        if ($this->shouldAddCacheHeaders($request)) {
            $response->headers->set('Cache-Control', 'public, max-age=3600');
            $response->headers->set('ETag', md5($response->getContent()));
        }

        return $response;
    }

    /**
     * Determine if the response should be compressed
     */
    private function shouldCompress(Request $request, Response $response): bool
    {
        // Check if client supports gzip
        $acceptEncoding = $request->headers->get('Accept-Encoding', '');
        if (!str_contains($acceptEncoding, 'gzip')) {
            return false;
        }

        // Check response size (only compress if worth it)
        $content = $response->getContent();
        if (!$content || strlen($content) < 1024) { // Less than 1KB
            return false;
        }

        // Check content type
        $contentType = $response->headers->get('Content-Type', '');
        $compressibleTypes = [
            'text/html',
            'text/css',
            'text/javascript',
            'application/javascript',
            'application/json',
            'text/xml',
            'application/xml',
        ];

        foreach ($compressibleTypes as $type) {
            if (str_contains($contentType, $type)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if cache headers should be added
     */
    private function shouldAddCacheHeaders(Request $request): bool
    {
        $path = $request->path();
        
        // Add cache headers for movie listings, celebrity pages, etc.
        $cacheablePaths = [
            'movies',
            'celebrities',
            'genres',
            'api/movies',
            'api/celebrities',
        ];

        foreach ($cacheablePaths as $cacheablePath) {
            if (str_starts_with($path, $cacheablePath)) {
                return true;
            }
        }

        return false;
    }
}