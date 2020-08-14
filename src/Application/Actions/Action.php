<?php

declare(strict_types=1);

namespace DImarkov\Application\Application\Actions;

use DImarkov\Application\Domain\DomainException\DomainRecordNotFoundException;
use JsonException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use const JSON_PRETTY_PRINT;

abstract class Action
{
    protected LoggerInterface $logger;

    /**
     * @var Request
     * @psalm-suppress PropertyNotSetInConstructor
     */
    protected Request $request;

    /**
     * @var Response
     * @psalm-suppress PropertyNotSetInConstructor
     */
    protected Response $response;

    protected array $args = [];

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @throws HttpNotFoundException
     * @throws HttpBadRequestException
     * @return Response
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        try {
            return $this->action();
        } catch (DomainRecordNotFoundException $e) {
            throw new HttpNotFoundException($request, $e->getMessage());
        }
    }

    /**
     * @throws DomainRecordNotFoundException
     * @throws HttpBadRequestException
     * @return Response
     */
    abstract protected function action(): Response;

    /**
     * @throws HttpBadRequestException
     * @return array
     */
    protected function getFormData(): array
    {
        try {
            /** @var array $input */
            $input = \json_decode((string) \file_get_contents('php://input'), true, 512, \JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new HttpBadRequestException($this->request, 'Malformed JSON input.', $exception);
        }

        return $input;
    }

    /**
     * @param string $name
     * @throws HttpBadRequestException
     * @return mixed
     */
    protected function resolveArg(string $name)
    {
        if (!isset($this->args[$name])) {
            throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
        }

        return $this->args[$name];
    }

    /**
     * @param null|array|object $data
     * @param int $statusCode
     * @throws JsonException
     * @return Response
     */
    protected function respondWithData($data = null, int $statusCode = 200): Response
    {
        $payload = new ActionPayload($statusCode, $data);

        return $this->respond($payload);
    }

    /**
     * @param ActionPayload $payload
     * @throws JsonException
     * @return Response
     */
    protected function respond(ActionPayload $payload): Response
    {
        $json = \json_encode($payload, \JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        $this->response->getBody()->write($json);

        return $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($payload->getStatusCode());
    }
}
