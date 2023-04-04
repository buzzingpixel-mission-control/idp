<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement;

use MissionControlIdp\IdentityManagement\Persistence\CreateIdentity;
use MissionControlIdp\IdentityManagement\Persistence\IdentityRecord;

readonly class IdentityRepository
{
    public function __construct(
        private CreateIdentity $createIdentity,
    ) {
    }

    public function createIdentity(NewIdentity $identity): ActionResult
    {
        return $this->createIdentity->create(
            IdentityRecord::fromNewIdentityEntity($identity),
        );
    }
}
