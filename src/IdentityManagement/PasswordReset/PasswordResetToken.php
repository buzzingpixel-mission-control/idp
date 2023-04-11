<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\PasswordReset;

use MissionControlIdp\IdentityManagement\PasswordReset\ValueObjects\CreatedAt;
use MissionControlIdp\IdentityManagement\PasswordReset\ValueObjects\Id;
use MissionControlIdp\IdentityManagement\PasswordReset\ValueObjects\IdentityId;
use MissionControlIdp\IdentityManagement\PasswordReset\ValueObjects\Token;

use function implode;

class PasswordResetToken
{
    public function __construct(
        public Id $id,
        public IdentityId $identityId,
        public Token $token,
        public CreatedAt $createdAt,
    ) {
    }

    public function key(): string
    {
        return implode('_', [
            'password_reset_token__identity_',
            $this->identityId->toNative(),
            '__id_',
            $this->id->toNative(),
        ]);
    }
}
