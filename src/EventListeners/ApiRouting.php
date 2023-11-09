<?php

declare(strict_types=1);

namespace MissionControlIdp\EventListeners;

use MissionControlBackend\Http\ApiApplyRoutesEvent;
use MissionControlIdp\Api\UserAdmin\AddEditUser\PostAddUserAction;
use MissionControlIdp\Api\UserAdmin\AddEditUser\PostEditUserAction;
use MissionControlIdp\Api\UserAdmin\GetAllUsersAction;
use MissionControlIdp\Api\UserInfo\EditEmail\PostEditEmailAction;
use MissionControlIdp\Api\UserInfo\EditName\PostEditNameAction;
use MissionControlIdp\Api\UserInfo\EditPassword\PostEditPasswordAction;
use MissionControlIdp\Api\UserInfo\EditTimezone\PostEditTimezoneAction;
use MissionControlIdp\Api\UserInfo\GetUserInfoAction;
use MissionControlIdp\Api\Users\GetUsersListAction;

class ApiRouting
{
    public function onApplyRoutes(ApiApplyRoutesEvent $event): void
    {
        GetUserInfoAction::registerRoute($event);
        PostEditNameAction::registerRoute($event);
        PostEditEmailAction::registerRoute($event);
        PostEditPasswordAction::registerRoute($event);
        PostEditTimezoneAction::registerRoute($event);
        GetAllUsersAction::registerRoute($event);
        PostAddUserAction::registerRoute($event);
        PostEditUserAction::registerRoute($event);
        GetUsersListAction::registerRoute($event);
    }
}
