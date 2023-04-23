<?php

declare(strict_types=1);

namespace MissionControlIdp\Authorize;

use MissionControlIdp\IdentityManagement\IdentityRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function json_encode;

use const JSON_PRETTY_PRINT;

readonly class RequireAdminMiddleware implements MiddlewareInterface
{
    public function __construct(
        private IdentityRepository $identityRepository,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {
        $identity = $this->identityRepository->findOneByRequestOrNull(
            $request,
        );

        if ($identity === null) {
            $response = $this->responseFactory->createResponse(403);

            $response->getBody()->write((string) json_encode([
                'error' => 'user_not_found',
                'error_description' => 'The user could not be found',
                'message' => 'The user could not be found',
            ], JSON_PRETTY_PRINT));

            return $response->withStatus(500);
        }

        if ($identity->isAdmin->isFalse()) {
            $response = $this->responseFactory->createResponse(403);

            $response->getBody()->write((string) json_encode([
                'error' => 'access_denied',
                'error_description' => 'User does not have appropriate access',
                'message' => 'You must be an admin to access this area',
            ], JSON_PRETTY_PRINT));

            return $response;
        }

        return $handler->handle($request);
    }
}
