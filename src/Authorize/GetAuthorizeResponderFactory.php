<?php

declare(strict_types=1);

namespace MissionControlIdp\Authorize;

readonly class GetAuthorizeResponderFactory
{
    public function __construct(private LogInResponder $logInResponder)
    {
    }

    public function create(): Responder
    {
        // TODO: Logic for if user is already logged in
        return $this->logInResponder;
    }
}
