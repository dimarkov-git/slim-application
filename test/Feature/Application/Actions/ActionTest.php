<?php

declare(strict_types=1);

namespace DImarkov\Application\Test\Feature\Application\Actions;

use DateTimeImmutable;
use DImarkov\Application\Application\Actions\Action;
use DImarkov\Application\Application\Actions\ActionPayload;
use DImarkov\Application\Test\Feature\AbstractFeatureTestCase;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

/**
 * @internal
 * @coversNothing
 */
final class ActionTest extends AbstractFeatureTestCase
{
    /**
     * @throws Exception
     */
    public function testActionSetsHttpCodeInRespond(): void
    {
        $app = $this->getAppInstance();
        $container = $app->getContainer();
        self::assertNotNull($container);
        /** @var LoggerInterface $logger */
        $logger = $container->get(LoggerInterface::class);

        $testAction = new class($logger) extends Action {
            protected function action(): Response
            {
                return $this->respond(
                    new ActionPayload(
                        202,
                        [
                            'willBeDoneAt' => (new DateTimeImmutable())->format(DateTimeImmutable::ATOM),
                        ]
                    )
                );
            }
        };

        $app->get('/test-action-response-code', $testAction);
        $request = $this->createRequest('GET', '/test-action-response-code');
        $response = $app->handle($request);

        self::assertEquals(202, $response->getStatusCode());
    }

    /**
     * @throws Exception
     */
    public function testActionSetsHttpCodeRespondData(): void
    {
        $app = $this->getAppInstance();
        $container = $app->getContainer();
        self::assertNotNull($container);
        /** @var LoggerInterface $logger */
        $logger = $container->get(LoggerInterface::class);

        $testAction = new class($logger) extends Action {
            protected function action(): Response
            {
                return $this->respondWithData(
                    [
                        'willBeDoneAt' => (new DateTimeImmutable())->format(DateTimeImmutable::ATOM),
                    ],
                    202
                );
            }
        };

        $app->get('/test-action-response-code', $testAction);
        $request = $this->createRequest('GET', '/test-action-response-code');
        $response = $app->handle($request);

        self::assertEquals(202, $response->getStatusCode());
    }
}
