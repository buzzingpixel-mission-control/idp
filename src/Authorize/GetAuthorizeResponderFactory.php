<?php

declare(strict_types=1);

namespace MissionControlIdp\Authorize;

use MissionControlIdp\Session\SessionRepository;
use Psr\Http\Message\ServerRequestInterface;

readonly class GetAuthorizeResponderFactory
{
    public function __construct(
        private LogInResponder $logInResponder,
        private SessionRepository $sessionRepository,
        private AuthorizeResponder $authorizeResponder,
    ) {
    }

    public function create(ServerRequestInterface $request): Responder
    {
        $identityAndSession = $this->sessionRepository->getSessionFromRequest(
            $request,
        );

        if (! $identityAndSession->isValid()) {
            return $this->logInResponder;
        }

        return $this->authorizeResponder;
    }
}
