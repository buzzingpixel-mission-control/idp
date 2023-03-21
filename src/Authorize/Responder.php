<?php

declare(strict_types=1);

namespace MissionControlIdp\Authorize;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface Responder
{
    public function respond(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface;
}
