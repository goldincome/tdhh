<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NoIndex
{
    /**
     * Mark the response as not indexable and keep the directive scoped to
     * this request.
     *
     * The directive is stored as a request attribute rather than shared
     * through View::share(). View::share() is global and persists for the
     * rest of the request, so a noindex route that rendered a shared view
     * would poison the indexable page that renders the same view later.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $directive
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $directive = 'noindex, nofollow')
    {
        $request->attributes->set('seoRobots', $directive);

        $response = $next($request);

        if (method_exists($response, 'header')) {
            $response->headers->set('X-Robots-Tag', $directive);
        }

        return $response;
    }
}
