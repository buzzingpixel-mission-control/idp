<?php

declare(strict_types=1);

namespace MissionControlIdp\ExceptionResponse;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

use function json_encode;

use const JSON_PRETTY_PRINT;

readonly class JsonResponder implements ExceptionResponder
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function respond(Throwable $exception): ResponseInterface
    {
        $code = $exception->getCode();

        $code = $code > 99 ? $code : 500;

        $response = $this->responseFactory->createResponse($code)
            ->withHeader('Content-Type', 'application/json');

        $response->getBody()->write((string) json_encode(
            ['error' => $exception->getMessage()],
            JSON_PRETTY_PRINT,
        ));

        return $response;
    }
}
