<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\Users\QueryParams;

readonly class FilterParams
{
    /** @param array<array-key, scalar|null> $data */
    public static function fromRawPostData(array $data): self
    {
        return new self(
            ActiveStatus::fromString(
                (string) ($data['active_status'] ?? ''),
            ),
            AdminStatus::fromString(
                (string) ($data['admin_status'] ?? ''),
            ),
        );
    }

    public function __construct(
        public ActiveStatus $activeStatus,
        public AdminStatus $adminStatus,
    ) {
    }
}
