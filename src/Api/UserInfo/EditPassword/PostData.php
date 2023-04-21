<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\UserInfo\EditPassword;

use Assert\Assert;

readonly class PostData
{
    /** @param string[] $rawPostData */
    public static function fromRawPostData(array $rawPostData): self
    {
        return new self(
            new Password($rawPostData['password'] ?? ''),
            new PasswordConfirmation(
                $rawPostData['passwordConfirm'] ?? '',
            ),
        );
    }

    public function __construct(
        public Password $password,
        public PasswordConfirmation $passwordConfirmation,
    ) {
        Assert::that($this->password->toNative())
            ->eq(
                $this->passwordConfirmation->toNative(),
                'Password must match confirmation',
            );
    }
}
