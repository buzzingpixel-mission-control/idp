<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\PasswordReset;

use MissionControlIdp\IdentityManagement\ActionResult;
use MissionControlIdp\IdentityManagement\Identity;

use function dd;

class SendPasswordResetEmail
{
    public function sendForIdentity(Identity $identity): ActionResult
    {
        // TODO: Build SendPasswordResetEmail::sendForIdentity
        dd('SendPasswordResetEmail::sendForIdentity', $identity);
    }
}
