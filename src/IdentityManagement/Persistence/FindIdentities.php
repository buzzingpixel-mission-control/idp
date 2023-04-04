<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\Persistence;

use MissionControlBackend\Persistence\MissionControlPdo;
use PDO;

readonly class FindIdentities
{
    public function __construct(private MissionControlPdo $pdo)
    {
    }

    public function findOne(
        FindIdentityParameters|null $parameters = null,
    ): IdentityRecord {
        $parameters ??= new FindIdentityParameters();

        $parameters = $parameters->with(limit: 1);

        return $this->findAll($parameters)->first();
    }

    public function findOneOrNull(
        FindIdentityParameters|null $parameters = null,
    ): IdentityRecord|null {
        $parameters ??= new FindIdentityParameters();

        $parameters = $parameters->with(limit: 1);

        return $this->findAll($parameters)->firstOrNull();
    }

    public function findAll(
        FindIdentityParameters|null $parameters = null,
    ): IdentityRecordCollection {
        $parameters ??= new FindIdentityParameters();

        $customQuery = $parameters->buildQuery();

        $statement = $this->pdo->prepare($customQuery->query);

        $statement->execute($customQuery->params);

        $results = $statement->fetchAll(
            PDO::FETCH_CLASS,
            IdentityRecord::class,
        );

        if ($results === false) {
            return new IdentityRecordCollection();
        }

        return new IdentityRecordCollection($results);
    }
}
