<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request and apply strict HTTP security headers.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $isLocal = app()->environment('local') || config('app.debug');

        // Security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // CSP configuration
        $scriptSrc = "'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://connect.facebook.net https://maps.googleapis.com https://www.google.com https://maps.google.com";
        $styleSrc = "'self' 'unsafe-inline' https://fonts.googleapis.com";
        $fontSrc = "'self' https://fonts.gstatic.com data:";
        $imgSrc = "'self' data: https: blob: https://maps.gstatic.com https://maps.googleapis.com https://*.google.com https://*.googleusercontent.com";
        $frameSrc = "'self' https://www.youtube.com https://player.vimeo.com https://maps.google.com https://www.google.com https://*.google.com";
        $connectSrc = "'self' https: https://connect.facebook.net https://maps.googleapis.com";

        if ($isLocal) {
            $scriptSrc .= " http://127.0.0.1:5173 http://localhost:5173 http://127.0.0.1:8000 http://localhost:8000";
            $styleSrc .= " http://127.0.0.1:5173 http://localhost:5173 http://127.0.0.1:8000 http://localhost:8000";
            $connectSrc .= " http://127.0.0.1:5173 http://localhost:5173 http://127.0.0.1:8000 http://localhost:8000 ws://127.0.0.1:5173 ws://localhost:5173 ws: wss:";
        }

        $csp = "default-src 'self' 'unsafe-inline' 'unsafe-eval' http: https: data: blob:; " .
               "script-src {$scriptSrc}; " .
               "style-src {$styleSrc}; " .
               "font-src {$fontSrc}; " .
               "img-src {$imgSrc}; " .
               "frame-src {$frameSrc}; " .
               "connect-src {$connectSrc};";

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
