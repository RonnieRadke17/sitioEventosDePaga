<?php

return [

    /*
        |--------------------------------------------------------------------------
        | Global Middleware
        |--------------------------------------------------------------------------
        |
        | Aquí puedes listar los middlewares que se ejecutarán en cada solicitud
        | a tu aplicación. Estos middlewares se ejecutan en el orden en que son
        | listados en esta matriz.
        |
    */

    'global' => [
        // Ejemplo: \App\Http\Middleware\CheckForMaintenanceMode::class,
    ],

    /*
        |--------------------------------------------------------------------------
        | Middleware Groups
        |--------------------------------------------------------------------------
        |
        | Aquí puedes agrupar varios middlewares bajo una sola clave, que puedes
        | asignar a rutas de forma masiva. Los grupos "web" y "api" son
        | proporcionados por defecto.
        |
    */

    'groups' => [
        'web' => [
            // \App\Http\Middleware\EncryptCookies::class,
            // \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            // \Illuminate\Session\Middleware\StartSession::class,
            // Otros middlewares de la web
        ],

        'api' => [
            // \Illuminate\Routing\Middleware\ThrottleRequests::class,
            // Otros middlewares de la API
        ],
    ],

    /*
        |--------------------------------------------------------------------------
        | Route Middleware
        |--------------------------------------------------------------------------
        |
        | Aquí puedes listar middlewares individuales que se pueden asignar
        | a rutas. Puedes asignar middlewares a rutas de manera individual
        | o agruparlos.
        |
    */

    'route' => [
        'role' => \App\Http\Middleware\RoleMiddleware::class, // Aquí está registrado el middleware
    // Otros middlewares
    ],


];
