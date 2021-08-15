<?php

require_once __DIR__.'/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

// enable use of facades
$app->withFacades();
// enable Eloquent
$app->withEloquent();
// Load auth config files
$app->configure('auth');
$app->configure('services');


/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
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
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

$app->middleware([
    \Barryvdh\Cors\HandleCors::class,
    App\Http\Middleware\CorsMiddleware::class,
]);

$app->routeMiddleware([
    'auth' => App\Http\Middleware\Authenticate::class,
    'customer' => App\Http\Middleware\CustomerMiddleware::class,
    // App\Http\Middleware\CorsMiddleware::class
    // 'client' => \Laravel\Passport\Http\Middleware\CheckClientCredentials::class,
]);
/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/
$app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\AuthServiceProvider::class);
$app->register(Tymon\JWTAuth\Providers\LumenServiceProvider::class);
$app->register(App\Providers\EventServiceProvider::class);
$app->register(Flipbox\LumenGenerator\LumenGeneratorServiceProvider::class);
$app->register(Laravel\Passport\PassportServiceProvider::class);
// $app->register(Dusterio\LumenPassport\PassportServiceProvider::class);
$app->register(Barryvdh\Cors\ServiceProvider::class);
$app->register(\Barryvdh\DomPDF\ServiceProvider::class);
$app->register(LaravelFCM\FCMServiceProvider::class);
$app->register(Illuminate\Redis\RedisServiceProvider::class);
// \Dusterio\LumenPassport\LumenPassport::routes($app->router, ['prefix' => 'v1/oauth'] );
\Dusterio\LumenPassport\LumenPassport::routes($app);
// $app->register(\Illuminate\Mail\MailServiceProvider::class);
$app->configure('dompdf');
$app->configure('mail');
$app->configure('firebase');
$app->configure('database');
class_alias(Barryvdh\DomPDF\Facade::class, 'PDF');
class_alias(LaravelFCM\Facades\FCM::class, 'FCM');
class_alias(LaravelFCM\Facades\FCMGroup::class, 'FCMGroup');

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/web.php';
});

return $app;
