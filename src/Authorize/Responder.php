<?php

declare(strict_types=1);

namespace MissionControlIdp\Authorize;

use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface Responder
{
    public function respond(
        ServerRequestInterface $request,
        ResponseInterface $response,
        AuthorizationRequest $authRequest,
    ): ResponseInterface;
}
