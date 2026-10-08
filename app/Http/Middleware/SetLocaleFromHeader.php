<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applique la langue envoyée par l'app mobile (en-tête Accept-Language)
 * pour les messages traduits via __() et les e-mails.
 */
class SetLocaleFromHeader
{
    public const SUPPORTED = ['en', 'fr'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->getPreferredLanguage(self::SUPPORTED);

        if ($locale && $request->hasHeader('Accept-Language')) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
