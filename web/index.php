<?php
use Corley\Middleware\App;
use Corley\Middleware\Factory\AppFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require __DIR__ . '/../app/bootstrap.php';

// Create request/response
$request = Request::createFromGlobals();
$response = new Response();

// Start the app
AppFactory::$DEBUG = $_ENV["APP_DEBUG"];
AppFactory::$CACHE_FOLDER = $_ENV["APP_CACHE_FOLDER"];
$app = AppFactory::createApp(__DIR__.'/../src', $container, $request, $response);
$app->setErrorHandler(function($rq, $rs, $e) {
    $rs->setContent($e->getMessage());
});
$response = $app->run($request, $response);
$response->send();
