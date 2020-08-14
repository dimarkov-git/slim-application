<?php

/**
 * @noinspection UsingInclusionReturnValueInspection
 * @noinspection PhpUnhandledExceptionInspection
 */

declare(strict_types=1);

use App\Application\Handlers\HttpErrorHandler;
use App\Application\Handlers\ShutdownHandler;
use App\Application\ResponseEmitter\ResponseEmitter;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

require_once __DIR__ . '/../config/bootstrap.php';

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

//if (false) { // Should be set to true in production
//    $containerBuilder->enableCompilation(__DIR__ . '/../.runtime/cache');
//}

// Set up settings
$settings = require __DIR__ . '/../config/slim/settings.php';
$settings($containerBuilder);

// Set up dependencies
$dependencies = require __DIR__ . '/../config/slim/dependencies.php';
$dependencies($containerBuilder);

// Set up repositories
$repositories = require __DIR__ . '/../config/slim/repositories.php';
$repositories($containerBuilder);

// Build PHP-DI Container instance
$container = $containerBuilder->build();

// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();
$callableResolver = $app->getCallableResolver();

// Register middleware
$middleware = require __DIR__ . '/../config/slim/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . '/../config/slim/routes.php';
$routes($app);

/** @var bool $displayErrorDetails */
$displayErrorDetails = $container->get('settings')['displayErrorDetails'];

// Create Request object from globals
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

// Create Error Handler
$responseFactory = $app->getResponseFactory();
$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);

// Create Shutdown Handler
$shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
register_shutdown_function($shutdownHandler);

// Add Routing Middleware
$app->addRoutingMiddleware();

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, false, false);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

// Run App & Emit Response
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);
