<?php

declare(strict_types=1);

namespace MissionControlIdp\EventListeners;

use MissionControlBackend\Http\AuthApplyRoutesEvent;
use MissionControlIdp\Authorize\GetAuthorizeAction;

class AuthRouting
{
    public function onApplyRoutes(AuthApplyRoutesEvent $event): void
    {
        GetAuthorizeAction::registerRoute($event);
    }
}
