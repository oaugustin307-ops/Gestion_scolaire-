<?php

use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\Middleware\StartSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Les routes API du groupe "guardian" utilisent une authentification
        // par SESSION (cookie), comme les routes web classiques, et non par
        // token (pas de Sanctum). Le groupe de middleware "api" n'inclut pas
        // la gestion des cookies/session par défaut (contrairement à "web") :
        // on l'ajoute donc explicitement pour que la connexion du parent
        // reste valable entre deux requêtes de l'application mobile.
        $middleware->api(prepend: [
            EncryptCookies::class,
            StartSession::class,
        ]);

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();