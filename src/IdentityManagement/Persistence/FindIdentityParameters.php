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
            $ids,
            $notIds,
            $limit,
            $offset,
            $orderBy,
            $sort,
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
            $addresses->with(array_merge(
                $addresses->addresses,
                [
                    new EmailAddress($emailAddress),
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
                $build->query . ' ' . $internalCustomQuery->query,
                array_merge(
                    $build->params,
                    $internalCustomQuery->params,
                ),
            );
        }

        return parent::buildQuery(
            static fn () => $buildCustomQuerySection,
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

                    $params[$key] = $email->toNative();

                    $i++;
                },
            );

            $query[] = 'AND email_address IN (' .
                implode(',', $in) .
                ')';
        }

        return new CustomQueryParams(
            implode(' ', $query),
            $params,
        );
    }
}
