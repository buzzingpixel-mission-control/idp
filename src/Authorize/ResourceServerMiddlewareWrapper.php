<?php

declare(strict_types=1);

namespace MissionControlIdp\Authorize;

use League\OAuth2\Server\Middleware\ResourceServerMiddleware;
use MissionControlIdp\IdentityManagement\IdentityRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function json_encode;

readonly class ResourceServerMiddlewareWrapper implements MiddlewareInterface
{
    public function __construct(
        private IdentityRepository $identityRepository,
        private ResponseFactoryInterface $responseFactory,
        private ResourceServerMiddleware $resourceServerMiddleware,
    ) {
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        return $this->resourceServerMiddleware->__invoke(
            $request,
            $this->responseFactory->createResponse(),
            function (
                ServerRequestInterface $request,
                ResponseInterface $response,
            ) use ($handler): ResponseInterface {
                return $this->innerProcess(
                    $request,
                    $response,
                    $handler,
                );
            },
        );
    }

    private function innerProcess(
        ServerRequestInterface $request,
        ResponseInterface $response,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        $identity = $this->identityRepository->findOneByRequestOrNull(
            $request,
        );

        if ($identity === null) {
            $response->getBody()->write((string) json_encode([
                'error' => 'user_not_found',
                'error_description' => 'The user could not be found',
                'message' => 'The user could not be found',
            ]));

            return $response->withStatus(500);
        }

        if ($identity->isActive->isFalse()) {
            $response->getBody()->write((string) json_encode([
                'error' => 'user_inactive',
                'error_description' => 'The user is inactive',
                'message' => 'Check with you admin to re-activate your account',
            ]));

            return $response->withStatus(403);
        }

        $request = $request->withAttribute('identity', $identity);

        return $handler->handle($request);
    }
}
