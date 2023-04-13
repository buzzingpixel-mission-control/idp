<?php

declare(strict_types=1);

namespace MissionControlIdp\Authorize;

use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use MissionControlIdp\AuthorizationServerFactory;
use MissionControlIdp\Session\SessionRepository;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

readonly class AuthorizeResponder implements Responder
{
    public function __construct(
        private SessionRepository $sessionRepository,
        private ResponseFactoryInterface $responseFactory,
        private AuthorizationServerFactory $authorizationServerFactory,
    ) {
    }

    public function respond(
        ServerRequestInterface $request,
        ResponseInterface $response,
        AuthorizationRequest $authRequest,
    ): ResponseInterface {
        $identityAndSession = $this->sessionRepository->getSessionFromRequest(
            $request,
        );

        $authRequest->setUser($identityAndSession->identity());

        $authRequest->setAuthorizationApproved(true);

        $server = $this->authorizationServerFactory->create();

        return $server->completeAuthorizationRequest(
            $authRequest,
            $this->responseFactory->createResponse(),
        );
    }
}
