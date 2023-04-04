<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\Persistence;

use MissionControlBackend\Persistence\CustomQueryParams;
use MissionControlBackend\Persistence\FetchParameters;
use MissionControlBackend\Persistence\Sort;
use MissionControlBackend\Persistence\StringCollection;
use MissionControlIdp\IdentityManagement\EmailAddress;
use MissionControlIdp\IdentityManagement\EmailAddressCollection;

use function array_merge;
use function implode;

readonly class FindIdentityParameters extends FetchParameters
{
    public function __construct(
        public EmailAddressCollection|null $emailAddresses = null,
        StringCollection|null $ids = null,
        StringCollection|null $notIds = null,
        int|null $limit = null,
        int|null $offset = null,
        string|null $orderBy = null,
        Sort|null $sort = null,
    ) {
        parent::__construct(
            ids: $ids,
            notIds: $notIds,
            limit: $limit,
            offset: $offset,
            orderBy: $orderBy,
            sort: $sort,
        );
    }

    public static function getTableName(): string
    {
        return IdentityRecord::getTableName();
    }

    public function tableName(): string
    {
        return self::getTableName();
    }

    public function withEmailAddress(string $emailAddress): static
    {
        $addresses = $this->emailAddresses ?? new EmailAddressCollection();

        return $this->with(
            emailAddresses: $addresses->with(addresses: array_merge(
                $addresses->addresses,
                [
                    new EmailAddress(emailAddress: $emailAddress),
                ],
            )),
        );
    }

    public function buildQuery(
        callable|null $buildCustomQuerySection = null,
    ): CustomQueryParams {
        $internalCustomQuery = $this->buildInternalCustomQuery();

        if ($buildCustomQuerySection === null) {
            $buildCustomQuerySection = $internalCustomQuery;
        } else {
            $build = $buildCustomQuerySection();

            $buildCustomQuerySection = new CustomQueryParams(
                query: $build->query . ' ' . $internalCustomQuery->query,
                params: array_merge(
                    $build->params,
                    $internalCustomQuery->params,
                ),
            );
        }

        return parent::buildQuery(
            buildCustomQuerySection: static fn () => $buildCustomQuerySection,
        );
    }

    private function buildInternalCustomQuery(): CustomQueryParams
    {
        $params = [];

        $query = [];

        if (
            $this->emailAddresses !== null &&
            $this->emailAddresses->count() > 0
        ) {
            $in = [];

            $i = 1;

            $this->emailAddresses->map(
                static function (EmailAddress $email) use (
                    &$i,
                    &$in,
                    &$params,
                ): void {
                    $key = 'email_address_' . $i;

                    $in[] = ':' . $key;

                    $params[$key] = $email->emailAddress;

                    $i++;
                },
            );

            $query[] = 'AND email_address IN (' .
                implode(',', $in) .
                ')';
        }

        return new CustomQueryParams(
            query: implode(' ', $query),
            params: $params,
        );
    }
}
