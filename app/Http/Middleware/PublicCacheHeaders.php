<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PublicCacheHeaders
{
    /**
     * Set appropriate cache headers for public-facing pages.
     *
     * Ensures browser and CDN caching for static/semi-static content while
     * respecting user sessions and authentication state. This improves Core
     * Web Vitals and reduces server load.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only cache successful GET/HEAD requests
        if (!in_array($response->getStatusCode(), [200, 203, 204, 206, 300, 301, 404, 405, 410, 414, 501])) {
            return $response;
        }

        if (!in_array($request->getMethod(), ['GET', 'HEAD'])) {
            return $response;
        }

        // Authenticated users: no public caching
        if (auth()->check() || auth('partner')->check() || auth('admin')->check()) {
            $response->headers->set('Cache-Control', 'private, must-revalidate');
            return $response;
        }

        // Public pages: aggressive caching
        // - max-age: 1 hour browser cache
        // - s-maxage: 24 hours CDN cache (if using a CDN like Cloudflare)
        // - stale-while-revalidate: serve stale while refreshing in background
        $response->headers->set('Cache-Control', 'public, max-age=3600, s-maxage=86400, stale-while-revalidate=604800');
        $response->headers->set('Vary', 'Accept-Encoding');

        return $response;
    }
}
