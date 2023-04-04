<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement;

use RuntimeException;

use function filter_var;

use const FILTER_VALIDATE_EMAIL;

class EmailAddress
{
    public function __construct(public string $emailAddress)
    {
        $validate = filter_var(
            $emailAddress,
            FILTER_VALIDATE_EMAIL,
        );

        if ($validate !== null && $validate !== false) {
            return;
        }

        throw new RuntimeException(
            'Email address must be valid format',
        );
    }
}
