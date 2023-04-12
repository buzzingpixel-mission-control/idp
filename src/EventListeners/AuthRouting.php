<?php

declare(strict_types=1);

namespace MissionControlIdp\EventListeners;

use MissionControlBackend\Http\AuthApplyRoutesEvent;
use MissionControlIdp\Authorize\GetAuthorizeAction;
use MissionControlIdp\Authorize\PostLogInAction;
use MissionControlIdp\IdentityManagement\PasswordReset\GetPasswordResetAction;
use MissionControlIdp\IdentityManagement\PasswordReset\PostPasswordResetAction;
use MissionControlIdp\IdentityManagement\PasswordReset\ResetWithToken\GetResetWithTokenAction;
use MissionControlIdp\IdentityManagement\PasswordReset\ResetWithToken\PostResetWithTokenAction;

class AuthRouting
{
    public function onApplyRoutes(AuthApplyRoutesEvent $event): void
    {
        GetAuthorizeAction::registerRoute($event);
        GetPasswordResetAction::registerRoute($event);
        PostPasswordResetAction::registerRoute($event);
        GetResetWithTokenAction::registerRoute($event);
        PostResetWithTokenAction::registerRoute($event);
        PostLogInAction::registerRoute($event);
    }
}
