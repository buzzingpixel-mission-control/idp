<?php

declare(strict_types=1);

namespace MissionControlIdp\Session;

use MissionControlIdp\IdentityManagement\Identity;

readonly class IdentityAndSession
{
    public function __construct(
        private Session|null $session = null,
        private Identity|null $identity = null,
    ) {
    }

    public function isValid(): bool
    {
        return $this->session !== null && $this->identity !== null;
    }

    public function session(): Session
    {
        /** @phpstan-ignore-next-line */
        return $this->session;
    }

    public function identity(): Identity
    {
        /** @phpstan-ignore-next-line */
        return $this->identity;
    }
}
