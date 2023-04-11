<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\PasswordReset\Persistence;

use MissionControlIdp\IdentityManagement\ActionResult;
use MissionControlIdp\IdentityManagement\PasswordReset\PasswordResetToken;
use MissionControlIdp\IdpRedisAdapter;

readonly class DeletePasswordResetToken
{
    public function __construct(private IdpRedisAdapter $storage)
    {
    }

    public function delete(PasswordResetToken $token): ActionResult
    {
        return new ActionResult(
            $this->storage->deleteItem($token->key()),
        );
    }
}
