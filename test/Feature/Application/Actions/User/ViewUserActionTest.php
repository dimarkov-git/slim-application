<?php

declare(strict_types=1);

namespace DImarkov\Application\Test\Feature\Application\Actions\User;

use DI\Container;
use DImarkov\Application\Application\Actions\ActionError;
use DImarkov\Application\Application\Actions\ActionPayload;
use DImarkov\Application\Application\Handlers\HttpErrorHandler;
use DImarkov\Application\Domain\User\User;
use DImarkov\Application\Domain\User\UserNotFoundException;
use DImarkov\Application\Domain\User\UserRepository;
use DImarkov\Application\Test\Feature\AbstractFeatureTestCase;
use Slim\Middleware\ErrorMiddleware;

/**
 * @internal
 * @coversDefaultClass \DImarkov\Application\Application\Actions\User\ViewUserAction
 */
final class ViewUserActionTest extends AbstractFeatureTestCase
{
    /**
     * @throws UserNotFoundException
     * @throws \JsonException
     */
    public function testAction(): void
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $user = new User(1, 'bill.gates', 'Bill', 'Gates');

        $userRepositoryProphecy = $this->prophesize(UserRepository::class);
        $userRepositoryProphecy
            ->findUserOfId(1)
            ->willReturn($user)
            ->shouldBeCalledOnce();

        $container->set(UserRepository::class, $userRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/users/1');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, $user);
        $serializedPayload = \json_encode($expectedPayload, \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT);

        self::assertEquals($serializedPayload, $payload);
    }

    /**
     * @throws UserNotFoundException
     * @throws \JsonException
     */
    public function testActionThrowsUserNotFoundException(): void
    {
        $app = $this->getAppInstance();

        $callableResolver = $app->getCallableResolver();
        $responseFactory = $app->getResponseFactory();

        $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
        $errorMiddleware = new ErrorMiddleware($callableResolver, $responseFactory, true, false, false);
        $errorMiddleware->setDefaultErrorHandler($errorHandler);

        $app->add($errorMiddleware);

        /** @var Container $container */
        $container = $app->getContainer();

        $userRepositoryProphecy = $this->prophesize(UserRepository::class);
        $userRepositoryProphecy
            ->findUserOfId(1)
            ->willThrow(new UserNotFoundException())
            ->shouldBeCalledOnce();

        $container->set(UserRepository::class, $userRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/users/1');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedError = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'The user you requested does not exist.');
        $expectedPayload = new ActionPayload(404, null, $expectedError);
        $serializedPayload = \json_encode($expectedPayload, \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT);

        self::assertEquals($serializedPayload, $payload);
    }
}
