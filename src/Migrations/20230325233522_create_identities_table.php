<?php

declare(strict_types=1);

use MissionControlBackend\Persistence\Migrations\ChangeMigration;
use MissionControlIdp\IdentityManagement\Persistence\IdentityRecord;

/** @noinspection PhpUnused */
/** @noinspection PhpIllegalPsrClassPathInspection */
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ClassFileName.NoMatch

class CreateIdentitiesTable extends ChangeMigration
{
    public function change(): void
    {
        $this->table(
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
        )->create();
    }
}
