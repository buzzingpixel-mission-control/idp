<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\Persistence;

use MissionControlBackend\Persistence\CustomQueryParams;
use MissionControlBackend\Persistence\FetchParameters;
use MissionControlBackend\Persistence\Sort;
use MissionControlBackend\Persistence\StringCollection;
use MissionControlIdp\IdentityManagement\ValueObjects\EmailAddress;
use MissionControlIdp\IdentityManagement\ValueObjects\EmailAddressCollection;

use function array_merge;
use function implode;

readonly class FindIdentityParameters extends FetchParameters
{
    public static function create(): self
    {
        return new self();
    }

    public function __construct(
        public bool|null $isActive = null,
        public bool|null $isAdmin = null,
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

    public function withIsActive(bool|null $isActive): static
    {
        return $this->with(isActive: $isActive);
    }

    public function withIsAdmin(bool|null $isActive): static
    {
        return $this->with(isAdmin: $isActive);
    }

    public function withEmailAddress(string $emailAddress): static
    {
        $addresses = $this->emailAddresses ?? new EmailAddressCollection();

        return $this->with(
            emailAddresses: $addresses->with(addresses: array_merge(
                $addresses->addresses,
                [
                    EmailAddress::fromNative($emailAddress),
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

        if ($this->isActive !== null) {
            $query[] = 'AND is_active = ' . ($this->isActive ? 'TRUE' : 'FALSE');
        }

        if ($this->isAdmin !== null) {
            $query[] = 'AND is_admin = ' . ($this->isAdmin ? 'TRUE' : 'FALSE');
        }

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
