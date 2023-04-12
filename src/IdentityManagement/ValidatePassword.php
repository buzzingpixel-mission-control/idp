<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement;

use MissionControlIdp\IdentityManagement\ValueObjects\Password;

use function password_needs_rehash;
use function password_verify;

use const PASSWORD_DEFAULT;

readonly class ValidatePassword
{
    public function __construct(private IdentityRepository $identityRepository)
    {
    }

    public function validate(
        Identity $identity,
        string $password,
        bool $rehashPasswordIfNeeded = true,
    ): bool {
        $hash = $identity->passwordHash->toNative();

        if ($hash === '') {
            return false;
        }

        if (! password_verify($password, $hash)) {
            return false;
        }

        if (! $rehashPasswordIfNeeded) {
            return true;
        }

        if (! password_needs_rehash($hash, PASSWORD_DEFAULT)) {
            return true;
        }

        $identity = $identity->with(newPassword: Password::fromNative(
            $password,
        ));

        $this->identityRepository->saveIdentity($identity);

        return true;
    }
}
