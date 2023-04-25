<?php

declare(strict_types=1);

use MissionControlBackend\Persistence\Migrations\ChangeMigration;
use MissionControlIdp\IdentityManagement\Persistence\IdentitiesTable;

/** @noinspection PhpUnused */
/** @noinspection PhpIllegalPsrClassPathInspection */
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace, Squiz.Classes.ClassFileName.NoMatch

class CreateIdentitiesTable extends ChangeMigration
{
    public function change(): void
    {
        IdentitiesTable::createSchema($this)->create();
    }
}
