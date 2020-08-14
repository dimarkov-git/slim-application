<?php

/**
 * @noinspection UsingInclusionReturnValueInspection
 */

declare(strict_types=1);

namespace DImarkov\Application\Test\Feature;

use DI\ContainerBuilder;
use Exception;
use PHPUnit\Framework\TestCase as AbstractTestCase;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Uri;

/**
 * @internal
 */
abstract class AbstractFeatureTestCase extends AbstractTestCase
{
    /**
     * @throws Exception
     * @return App
     */
    protected function getAppInstance(): App
    {
        // Instantiate PHP-DI ContainerBuilder
        $containerBuilder = new ContainerBuilder();

        // Container intentionally not compiled for tests.

        // Set up settings
        $settings = require __DIR__ . '/../../config/slim/settings.php';
        $settings($containerBuilder);

        // Set up dependencies
        $dependencies = require __DIR__ . '/../../config/slim/dependencies.php';
        $dependencies($containerBuilder);

        // Set up repositories
        $repositories = require __DIR__ . '/../../config/slim/repositories.php';
        $repositories($containerBuilder);

        // Build PHP-DI Container instance
        $container = $containerBuilder->build();

        // Instantiate the app
        AppFactory::setContainer($container);
        $app = AppFactory::create();

        // Register middleware
        $middleware = require __DIR__ . '/../../config/slim/middleware.php';
        $middleware($app);

        // Register routes
        $routes = require __DIR__ . '/../../config/slim/routes.php';
        $routes($app);

        return $app;
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $headers
     * @psalm-param array<string, string|list<string>> $headers
     * @param array $cookies
     * @param array $serverParams
     * @return Request
     */
    protected function createRequest(
        string $method,
        string $path,
        array $headers = ['HTTP_ACCEPT' => 'application/json'],
        array $cookies = [],
        array $serverParams = []
    ): Request {
        $uri = new Uri('', '', 80, $path);
        $handle = \fopen('php://temp', 'wb+');

        if ($handle === false) {
            throw new \RuntimeException('Handle is false');
        }
        $stream = (new StreamFactory())->createStreamFromResource($handle);

        $h = new Headers();

        foreach ($headers as $name => $value) {
            $h->addHeader($name, $value);
        }

        return new SlimRequest($method, $uri, $h, $cookies, $serverParams, $stream);
    }
}
