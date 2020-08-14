<?php

declare(strict_types=1);

namespace DImarkov\Application\Test\Feature\Application\Actions\User;

use DI\Container;
use DImarkov\Application\Application\Actions\ActionPayload;
use DImarkov\Application\Domain\User\User;
use DImarkov\Application\Domain\User\UserRepository;
use DImarkov\Application\Test\Feature\AbstractFeatureTestCase;

/**
 * @internal
 * @coversDefaultClass \DImarkov\Application\Application\Actions\User\ListUsersAction
 */
final class ListUserActionTest extends AbstractFeatureTestCase
{
    /**
     * @throws \Exception
     */
    public function testAction(): void
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $user = new User(1, 'bill.gates', 'Bill', 'Gates');

        $userRepositoryProphecy = $this->prophesize(UserRepository::class);
        $userRepositoryProphecy
            ->findAll()
            ->willReturn([$user])
            ->shouldBeCalledOnce();

        $container->set(UserRepository::class, $userRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/users');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, [$user]);
        $serializedPayload = \json_encode($expectedPayload, \JSON_PRETTY_PRINT);

        self::assertEquals($serializedPayload, $payload);
    }
}
