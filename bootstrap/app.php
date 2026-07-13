<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
<<<<<<< HEAD
        api: __DIR__.'/../routes/api.php',
=======
>>>>>>> 2fcb0d02d284ef33586cab99db3b7e99f28e3c86
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
<<<<<<< HEAD
        $middleware->validateCsrfTokens(except:
            [
                'api/telegram/webhook',
                '/*',
            ]
        )
        ->appendToGroup('web', [
            \App\Http\Middleware\SetAppLocale::class,
        ]);
=======
        //
>>>>>>> 2fcb0d02d284ef33586cab99db3b7e99f28e3c86
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
