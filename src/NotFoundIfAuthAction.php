<?php

declare(strict_types=1);

namespace MissionControlIdp;

use MissionControlIdp\Session\SessionRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpNotFoundException;

readonly class NotFoundIfAuthAction implements MiddlewareInterface
{
    public function __construct(private SessionRepository $sessionRepository)
    {
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        $identityAndSession = $this->sessionRepository->getSessionFromRequest(
            $request,
        );

        if ($identityAndSession->isValid()) {
            throw new HttpNotFoundException($request);
        }

        return $handler->handle($request);
    }
}
