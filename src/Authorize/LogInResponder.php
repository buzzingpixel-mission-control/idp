<?php

declare(strict_types=1);

namespace MissionControlIdp\Authorize;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function dd;

class LogInResponder implements Responder
{
    public function respond(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        dd('LogInResponder');
    }
}
