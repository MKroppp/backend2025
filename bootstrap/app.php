<?php

require_once __DIR__.'/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

/*
|--------------------------------------------------------------------------|
| Create The Application
|--------------------------------------------------------------------------|
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
| 
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

$app->withFacades();

$app->withEloquent();

/*
|--------------------------------------------------------------------------|
| Register Container Bindings
|--------------------------------------------------------------------------|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------|
| Register Config Files
|--------------------------------------------------------------------------|
*/

$app->configure('app');

$app->configure('auth');


/*
|--------------------------------------------------------------------------|
| Register Middleware
|--------------------------------------------------------------------------|
*/

$app->middleware([
    App\Http\Middleware\ExampleMiddleware::class
]);

$app->routeMiddleware([
    'auth' => \Tymon\JWTAuth\Middleware\Authenticate::class,  // Мiddleware для JWT
]);

/*
|--------------------------------------------------------------------------|
| Register Service Providers
|--------------------------------------------------------------------------|
*/

$app->register(Tymon\JWTAuth\Providers\LumenServiceProvider::class);

/*
|--------------------------------------------------------------------------|
| Load The Application Routes
|--------------------------------------------------------------------------|
*/

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/web.php';
});

return $app;
