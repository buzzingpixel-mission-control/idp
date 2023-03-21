<?php

declare(strict_types=1);

namespace MissionControlIdp\EventListeners;

use MissionControlBackend\Http\AuthSetRoutesEvent;
use MissionControlIdp\Authorize\GetAuthorizeAction;

class AuthRouting
{
    public function onApplyRoutes(AuthSetRoutesEvent $event): void
    {
        GetAuthorizeAction::registerRoute($event->routeCollector);
    }
}
