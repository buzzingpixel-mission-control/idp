<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\Persistence;

use MissionControlBackend\Persistence\Record;
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

    public static function fromNewIdentityEntity(NewIdentity $identity): self
    {
        $record = new self();

        $record->email_address = $identity->emailAddress->emailAddress;

        $record->is_admin = $identity->isAdmin;

        $record->name = $identity->name;

        if ($identity->password !== '') {
            $record->setPasswordHashFromPassword($identity->password);
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
