<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\Persistence;

use MissionControlBackend\Persistence\Record;

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

    /** Primary key */
    public string $id = '';

    public string $email_address = '';

    public string $name = '';

    public string $password_hash = '';

    public string $created_at = '';
}
