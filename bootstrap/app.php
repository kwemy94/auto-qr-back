<?php

use App\Http\Middleware\ForceJsonResponse;
use App\Http\Middleware\SetLocaleFromHeader;
use App\Http\Middleware\SetWebLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend:[
            ForceJsonResponse::class,
            SetLocaleFromHeader::class,
        ]);
        $middleware->web(append: [
            SetWebLocale::class,
        ]);
        // Cookie de langue lisible en clair : la page 404 (route inconnue,
        // sans middleware web) s'en sert aussi.
        $middleware->encryptCookies(except: [
            SetWebLocale::COOKIE,
        ]);
        $middleware->validateCsrfTokens(except: [
            'n/*/send',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
