<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request and set application locale.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->header('X-App-Locale') 
            ?? $request->get('locale') 
            ?? $request->cookie('emisha_locale') 
            ?? 'bn';

        if (!in_array($locale, ['bn', 'en'])) {
            $acceptLanguage = $request->header('Accept-Language', '');
            $locale = str_starts_with($acceptLanguage, 'en') ? 'en' : 'bn';
        }

        app()->setLocale($locale);

        $response = $next($request);
        $response->headers->set('Content-Language', $locale);

        return $response;
    }
}
