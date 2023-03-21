<?php

declare(strict_types=1);

namespace MissionControlIdp\ExceptionResponse;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Throwable;

readonly class ExceptionAction
{
    public function __construct(
        private LoggerInterface $logger,
        private ResponderFactory $responderFactory,
    ) {
    }

    public function invoke(
        Throwable $exception,
        ServerRequestInterface $request,
    ): ResponseInterface {
        $this->logger->error(
            'Responding to exception.',
            ['exception' => $exception],
        );

        return $this->responderFactory->create($request)->respond(
            $exception,
        );
    }
}
