<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\UserAdmin\AddEditUser;

use MissionControlIdp\Api\UserAdmin\AddEditUser\ValueObjects\Email;
use MissionControlIdp\Api\UserAdmin\AddEditUser\ValueObjects\IsAdmin;
use MissionControlIdp\Api\UserAdmin\AddEditUser\ValueObjects\Name;
use MissionControlIdp\Api\UserAdmin\AddEditUser\ValueObjects\Password;
use MissionControlIdp\Api\UserAdmin\AddEditUser\ValueObjects\Timezone;

readonly class PostDataAddUser
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
