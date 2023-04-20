<?php

declare(strict_types=1);

namespace MissionControlIdp\EventListeners;

use MissionControlBackend\Http\ApiApplyRoutesEvent;
use MissionControlIdp\Api\GetUserInfoAction;

class ApiRouting
{
    public function onApplyRoutes(ApiApplyRoutesEvent $event): void
    {
        GetUserInfoAction::registerRoute($event);
    }
}
