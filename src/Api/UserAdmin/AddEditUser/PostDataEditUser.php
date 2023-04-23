<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\UserAdmin\AddEditUser;

use MissionControlIdp\Api\UserAdmin\AddEditUser\ValueObjects\Email;
use MissionControlIdp\Api\UserAdmin\AddEditUser\ValueObjects\IsActive;
use MissionControlIdp\Api\UserAdmin\AddEditUser\ValueObjects\IsAdmin;
use MissionControlIdp\Api\UserAdmin\AddEditUser\ValueObjects\Name;
use MissionControlIdp\Api\UserAdmin\AddEditUser\ValueObjects\Password;
use MissionControlIdp\Api\UserAdmin\AddEditUser\ValueObjects\Timezone;
use MissionControlIdp\Api\UserAdmin\AddEditUser\ValueObjects\UserId;

readonly class PostDataEditUser
{
    /** @param string[] $data */
    public static function fromRawPostData(array $data): self
    {
        return new self(
            UserId::fromNative($data['user_id'] ?? ''),
            Email::fromNative($data['email'] ?? ''),
            Name::fromNative($data['name'] ?? ''),
            Password::fromNative($data['password'] ?? ''),
            IsAdmin::fromString($data['is_admin'] ?? ''),
            Timezone::fromString($data['timezone'] ?? ''),
            IsActive::fromString($data['is_active'] ?? ''),
        );
    }

    public function __construct(
        public UserId $userId,
        public Email $email,
        public Name $name,
        public Password $password,
        public IsAdmin $isAdmin,
        public Timezone $timezone,
        public IsActive $isActive,
    ) {
    }
}
