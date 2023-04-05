<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement;

use MissionControlIdp\IdentityManagement\Persistence\CreateIdentity;
use MissionControlIdp\IdentityManagement\Persistence\FindIdentities;
use MissionControlIdp\IdentityManagement\Persistence\FindIdentityParameters;
use MissionControlIdp\IdentityManagement\Persistence\IdentityRecord;
use MissionControlIdp\IdentityManagement\Persistence\SaveIdentity;

readonly class IdentityRepository
{
    public function __construct(
        private SaveIdentity $saveIdentity,
        private CreateIdentity $createIdentity,
        private FindIdentities $findIdentities,
    ) {
    }

    public function createIdentity(NewIdentity $identity): ActionResult
    {
        return $this->createIdentity->create(
            IdentityRecord::fromNewIdentityEntity($identity),
        );
    }

    public function saveIdentity(Identity $identity): ActionResult
    {
        return $this->saveIdentity->save(IdentityRecord::fromEntity(
            $identity,
        ));
    }

    public function findOneById(string $id): Identity
    {
        return Identity::fromRecord(
            $this->findIdentities->findOne(
                (new FindIdentityParameters())->withId($id),
            ),
        );
    }

    public function findOneByIdOrNull(string $id): Identity|null
    {
        $record = $this->findIdentities->findOneOrNull(
            (new FindIdentityParameters())->withId($id),
        );

        if ($record === null) {
            return null;
        }

        return Identity::fromRecord($record);
    }

    public function findOneByEmailAddress(string $emailAddress): Identity
    {
        return Identity::fromRecord(
            $this->findIdentities->findOne(
                (new FindIdentityParameters())->withEmailAddress(
                    $emailAddress,
                ),
            ),
        );
    }

    public function findOneByEmailAddressOrNull(
        string $emailAddress,
    ): Identity|null {
        $record = $this->findIdentities->findOneOrNull(
            (new FindIdentityParameters())->withEmailAddress(
                $emailAddress,
            ),
        );

        if ($record === null) {
            return null;
        }

        return Identity::fromRecord($record);
    }

    public function findAll(
        FindIdentityParameters|null $parameters = null,
    ): IdentityCollection {
        $records = $this->findIdentities->findAll($parameters);

        /** @phpstan-ignore-next-line */
        return new IdentityCollection($records->map(
            static fn (IdentityRecord $r) => Identity::fromRecord(
                $r,
            ),
        ));
    }
}
