<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * Langue des pages web publiques (accueil, scan, pages légales, erreurs).
 * Priorité : ?lang=en|fr, puis le choix mémorisé (cookie « lang »),
 * sinon l'anglais par défaut. Un ?lang valide est mémorisé un an.
 * Exception : un appel JSON de l'app mobile (ex. POST /n/{token}/send)
 * suit son en-tête Accept-Language, comme l'API.
 */
class SetWebLocale
{
    public const SUPPORTED = ['en', 'fr'];
    public const DEFAULT = 'en';
    public const COOKIE = 'lang';

    public static function resolve(Request $request): string
    {
        $header = $request->wantsJson() && $request->hasHeader('Accept-Language')
            ? $request->getPreferredLanguage(self::SUPPORTED)
            : null;

        foreach ([$request->query('lang'), $header, $request->cookie(self::COOKIE)] as $candidate) {
            if (in_array($candidate, self::SUPPORTED, true)) {
                return $candidate;
            }
        }

        return self::DEFAULT;
    }

    public function handle(Request $request, Closure $next): Response
    {
        app()->setLocale(self::resolve($request));

        $query = $request->query('lang');
        if (in_array($query, self::SUPPORTED, true) && $query !== $request->cookie(self::COOKIE)) {
            Cookie::queue(self::COOKIE, $query, 60 * 24 * 365, null, null, null, false);
        }

        return $next($request);
    }
}
