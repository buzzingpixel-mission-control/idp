<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement;

use Spatie\Cloneable\Cloneable;

readonly class NewIdentity
{
    use Cloneable;

    public function __construct(
        public EmailAddress $emailAddress,
        public bool $isAdmin = false,
        public string $name = '',
        public string $password = '',
    ) {
    }
}
