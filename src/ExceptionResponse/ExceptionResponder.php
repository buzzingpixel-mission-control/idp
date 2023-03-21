<?php

declare(strict_types=1);

namespace MissionControlIdp\ExceptionResponse;

use Psr\Http\Message\ResponseInterface;
use Throwable;

interface ExceptionResponder
{
    public function respond(Throwable $exception): ResponseInterface;
}
