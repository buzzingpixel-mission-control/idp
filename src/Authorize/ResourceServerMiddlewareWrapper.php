<?php

declare(strict_types=1);

namespace MissionControlIdp\Authorize;

use League\OAuth2\Server\Middleware\ResourceServerMiddleware;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class ResourceServerMiddlewareWrapper implements MiddlewareInterface
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private ResourceServerMiddleware $resourceServerMiddleware,
    ) {
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        $next = static function (ServerRequestInterface $request) use (
            $handler,
        ) {
            return $handler->handle($request);
        };

        return $this->resourceServerMiddleware->__invoke(
            $request,
            $this->responseFactory->createResponse(),
            $next,
        );
    }
}
