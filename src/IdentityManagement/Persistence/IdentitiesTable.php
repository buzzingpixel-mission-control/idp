<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\Persistence;

use Phinx\Db\Table;
use Phinx\Migration\MigrationInterface;

class IdentitiesTable
{
    public const TABLE_NAME = 'identities';

    public static function createSchema(MigrationInterface $migration): Table
    {
        return $migration->table(
            IdentityRecord::getTableName(),
            [
                'id' => false,
                'primary_key' => ['id'],
            ],
        )->addColumn(
            columnName: 'id',
            type: 'uuid',
        )->addColumn(
            columnName: 'is_admin',
            type: 'boolean',
            options: ['default' => 0],
        )->addColumn(
            columnName: 'email_address',
            type: 'text',
        )->addColumn(
            columnName: 'name',
            type: 'text',
        )->addColumn(
            columnName: 'password_hash',
            type: 'string',
        )->addColumn(
            columnName: 'is_active',
            type: 'boolean',
            options: ['default' => 1],
        )->addColumn(
            columnName: 'timezone',
            type: 'string',
        )->addColumn(
            columnName: 'created_at',
            type: 'datetime',
        )
            ->addIndex(['email_address'])
            ->addIndex(['name'])
            ->addIndex(['created_at']);
    }
}
