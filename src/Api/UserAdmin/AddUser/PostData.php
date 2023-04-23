<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\UserAdmin\AddUser;

use MissionControlIdp\Api\UserAdmin\AddUser\ValueObjects\Email;
use MissionControlIdp\Api\UserAdmin\AddUser\ValueObjects\IsAdmin;
use MissionControlIdp\Api\UserAdmin\AddUser\ValueObjects\Name;
use MissionControlIdp\Api\UserAdmin\AddUser\ValueObjects\Password;
use MissionControlIdp\Api\UserAdmin\AddUser\ValueObjects\Timezone;

readonly class PostData
{
    /** @param string[] $data */
    public static function fromRawPostData(array $data): self
    {
        return new self(
            Email::fromNative($data['email'] ?? ''),
            Name::fromNative($data['name'] ?? ''),
            Password::fromNative($data['password'] ?? ''),
            IsAdmin::fromString($data['is_admin'] ?? ''),
            Timezone::fromString($data['timezone'] ?? ''),
        );
    }

    public function __construct(
        public Email $email,
        public Name $name,
        public Password $password,
        public IsAdmin $isAdmin,
        public Timezone $timezone,
    ) {
    }
}
