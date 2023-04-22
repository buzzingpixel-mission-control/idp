<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement;

use DateTimeZone;
use League\OAuth2\Server\Entities\UserEntityInterface;
use MissionControlIdp\IdentityManagement\Persistence\IdentityRecord;
use MissionControlIdp\IdentityManagement\ValueObjects\CreatedAt;
use MissionControlIdp\IdentityManagement\ValueObjects\EmailAddress;
use MissionControlIdp\IdentityManagement\ValueObjects\Id;
use MissionControlIdp\IdentityManagement\ValueObjects\IsActive;
use MissionControlIdp\IdentityManagement\ValueObjects\IsAdmin;
use MissionControlIdp\IdentityManagement\ValueObjects\Name;
use MissionControlIdp\IdentityManagement\ValueObjects\NullValue;
use MissionControlIdp\IdentityManagement\ValueObjects\Password;
use MissionControlIdp\IdentityManagement\ValueObjects\PasswordHash;
use MissionControlIdp\IdentityManagement\ValueObjects\Timezone;
use Spatie\Cloneable\Cloneable;

// phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

readonly class Identity implements UserEntityInterface
{
    use Cloneable;

    public static function fromRecord(IdentityRecord $record): self
    {
        return new self(
            Id::fromNative($record->id),
            IsAdmin::fromNative($record->is_admin),
            EmailAddress::fromNative($record->email_address),
            Name::fromNative($record->name),
            PasswordHash::fromNative($record->password_hash),
            CreatedAt::fromNative($record->created_at),
            IsActive::fromNative($record->is_active),
            Timezone::fromNative($record->timezone),
        );
    }

    public function __construct(
        public Id $id,
        public IsAdmin $isAdmin,
        public EmailAddress $emailAddress,
        public Name $name,
        public PasswordHash $passwordHash,
        public CreatedAt $createdAt,
        public IsActive $isActive = new IsActive(true),
        public Timezone $timezone = new Timezone(new DateTimeZone('US/Central')),
        public Password|NullValue $newPassword = new NullValue(),
    ) {
    }

    public function getIdentifier(): string
    {
        return $this->id->toNative();
    }

    public function nameOrEmail(): string
    {
        return $this->name->isNotEmpty() ?
            $this->name->toNative() :
            $this->emailAddress->toNative();
    }

    /**
     * @param string[] $without
     *
     * @return array<string, scalar|null>
     */
    public function asArray(
        array $without = [
            'passwordHash',
            'newPassword',
        ],
    ): array {
        $array = [
            'id' => $this->id->toNative(),
            'isAdmin' => $this->isAdmin->toNative(),
            'emailAddress' => $this->emailAddress->toNative(),
            'name' => $this->name->toNative(),
            'passwordHash' => $this->passwordHash->toNative(),
            'createdAt' => $this->createdAt->toNative(),
            'isActive' => $this->isActive->toNative(),
            'timezone' => $this->timezone->toNative(),
            'newPassword' => $this->newPassword->toNative(),
        ];

        foreach ($without as $item) {
            unset($array[$item]);
        }

        return $array;
    }
}
