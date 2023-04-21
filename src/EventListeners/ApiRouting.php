<?php

declare(strict_types=1);

namespace MissionControlIdp\EventListeners;

use MissionControlBackend\Http\ApiApplyRoutesEvent;
use MissionControlIdp\Api\UserInfo\EditEmail\PostEditEmailAction;
use MissionControlIdp\Api\UserInfo\EditName\PostEditNameAction;
use MissionControlIdp\Api\UserInfo\GetUserInfoAction;

class ApiRouting
{
    public function onApplyRoutes(ApiApplyRoutesEvent $event): void
    {
        GetUserInfoAction::registerRoute($event);
        PostEditNameAction::registerRoute($event);
        PostEditEmailAction::registerRoute($event);
    }
}
