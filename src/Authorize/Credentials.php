<?php

declare(strict_types=1);

namespace MissionControlIdp\Authorize;

use MissionControlIdp\Authorize\ValueObjects\EmailAddress;
use MissionControlIdp\Authorize\ValueObjects\Password;

use function is_string;

class Credentials
{
    /** @param mixed[] $credentials */
    public static function fromArray(array $credentials): self
    {
        $email = $credentials['email'] ?? '';
        $email = is_string($email) ? $email : '';

        $password = $credentials['password'] ?? '';
        $password = is_string($password) ? $password : '';

        return new self(
            email: EmailAddress::fromNative($email),
            password: Password::fromNative($password),
        );
    }

    public function __construct(
        public EmailAddress $email,
        public Password $password,
    ) {
    }
}
