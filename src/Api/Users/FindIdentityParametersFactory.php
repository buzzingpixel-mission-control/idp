<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\Users;

use MissionControlBackend\Persistence\Sort;
use MissionControlIdp\Api\Users\QueryParams\ActiveStatus;
use MissionControlIdp\Api\Users\QueryParams\AdminStatus;
use MissionControlIdp\Api\Users\QueryParams\FilterParams;
use MissionControlIdp\IdentityManagement\Persistence\FindIdentityParameters;

readonly class FindIdentityParametersFactory
{
    public function create(FilterParams $filterParams): FindIdentityParameters
    {
        $parameters = FindIdentityParameters::create()
            ->withOrderBy('email_address')
            ->withSort(Sort::ASC);

        $parameters = match ($filterParams->activeStatus) {
            ActiveStatus::ALL => $parameters->withIsActive(null),
            ActiveStatus::ACTIVE => $parameters->withIsActive(true),
            ActiveStatus::INACTIVE => $parameters->withIsActive(false),
        };

        $parameters = match ($filterParams->adminStatus) {
            AdminStatus::ALL => $parameters->withIsAdmin(null),
            AdminStatus::IS_ADMIN => $parameters->withIsAdmin(true),
            AdminStatus::IS_NOT_ADMIN => $parameters->withIsAdmin(false),
        };

        return $parameters;
    }
}
