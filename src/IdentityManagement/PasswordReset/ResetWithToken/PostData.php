<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\PasswordReset\ResetWithToken;

use function count;

class PostData
{
    public bool $hasErrors;

    /** @var string[] */
    public array $errors;

    /** @param scalar[] $data */
    public static function fromArray(array $data): self
    {
        return new self(
            (string) ($data['password'] ?? ''),
            (string) ($data['passwordConfirm'] ?? ''),
        );
    }

    public function __construct(
        public string $password,
        public string $passwordConfirm,
    ) {
        $errors = [];

        if ($password !== $passwordConfirm) {
            $errors[] = 'Password must match confirmation';
        }

        $this->hasErrors = count($errors) > 0;

        $this->errors = $errors;
    }
}
