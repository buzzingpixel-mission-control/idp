<?php

declare(strict_types=1);

namespace MissionControlIdp\Session;

use MissionControlIdp\Session\ValueObjects\CreatedAt;
use MissionControlIdp\Session\ValueObjects\Id;
use MissionControlIdp\Session\ValueObjects\IdentityId;

use function implode;

readonly class Session
{
    public const COOKIE_NAME = 'identity_session_key';

    public function __construct(
        public Id $id,
        public IdentityId $identityId,
        public CreatedAt $createdAt,
    ) {
    }

    public function sessionKey(): string
    {
        return implode('_', [
            'identity_session',
            $this->identityId->toNative(),
            $this->id->toNative(),
        ]);
    }
}
