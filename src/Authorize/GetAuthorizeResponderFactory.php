<?php

declare(strict_types=1);

namespace MissionControlIdp\Authorize;

use function dd;

class GetAuthorizeResponderFactory
{
    public function create(): Responder
    {
        dd('GetAuthorizeResponderFactory');
    }
}
