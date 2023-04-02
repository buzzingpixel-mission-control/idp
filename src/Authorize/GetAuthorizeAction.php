<?php

declare(strict_types=1);

namespace MissionControlIdp\Authorize;

use MissionControlBackend\Http\ApplyRoutesEvent;
use MissionControlIdp\ExceptionResponse\ExceptionAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

readonly class GetAuthorizeAction
{
    public static function registerRoute(ApplyRoutesEvent $event): void
    {
        $event->get('/oauth2/authorize', self::class);
    }

    public function __construct(
        private ExceptionAction $exceptionAction,
        private GetAuthorizeResponderFactory $responderFactory,
    ) {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        try {
            return $this->responderFactory->create()->respond(
                request: $request,
                response: $response,
            );
        } catch (Throwable $exception) {
            return $this->exceptionAction->invoke(
                exception: $exception,
                request: $request,
            );
        }
    }
}
