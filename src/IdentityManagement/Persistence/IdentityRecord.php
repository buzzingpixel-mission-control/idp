<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\Persistence;

use MissionControlBackend\Persistence\Record;
use MissionControlIdp\IdentityManagement\Identity;
use MissionControlIdp\IdentityManagement\NewIdentity;

use function password_hash;

use const PASSWORD_DEFAULT;

// phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

class IdentityRecord extends Record
{
    private const TABLE_NAME = 'identities';

    public static function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    public function tableName(): string
    {
        return self::TABLE_NAME;
    }

    public static function fromNewIdentityEntity(NewIdentity $entity): self
    {
        $record = new self();

        $record->email_address = $entity->emailAddress->toNative();

        $record->is_admin = $entity->isAdmin->toNative();

        $record->name = $entity->name->toNative();

        if ($entity->password->toNative() !== '') {
            $record->setPasswordHashFromPassword(
                $entity->password->toNative(),
            );
        }

        return $record;
    }

    public static function fromEntity(Identity $entity): self
    {
        $record = new self();

        $record->id = $entity->id->toNative();

        $record->is_admin = $entity->isAdmin->toNative();

        $record->email_address = $entity->emailAddress->toNative();

        $record->name = $entity->name->toNative();

        $record->password_hash = $entity->passwordHash->toNative();

        $record->created_at = $entity->createdAt->toNative();

        if (! $entity->newPassword->isNull()) {
            $record->setPasswordHashFromPassword(
                (string) $entity->newPassword->toNative(),
            );
        }

        return $record;
    }

    /** Primary key */
    public string $id = '';

    public bool $is_admin = false;

    public string $email_address = '';

    public string $name = '';

    public string $password_hash = '';

    public string $created_at = '';

    public function setPasswordHashFromPassword(string $password): void
    {
        $this->password_hash = password_hash(
            $password,
            PASSWORD_DEFAULT,
        );
    }
}
