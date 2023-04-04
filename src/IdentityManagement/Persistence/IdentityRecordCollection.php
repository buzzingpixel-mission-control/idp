<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\Persistence;

use function array_map;
use function array_values;
use function count;

class IdentityRecordCollection
{
    /** @var IdentityRecord[] */
    public array $records;

    /** @param IdentityRecord[] $records */
    public function __construct(array $records = [])
    {
        $this->records = array_values(array_map(
            static fn (IdentityRecord $r) => $r,
            $records,
        ));
    }

    public function first(): IdentityRecord
    {
        return $this->records[0];
    }

    public function firstOrNull(): IdentityRecord|null
    {
        return $this->records[0] ?? null;
    }

    /** @return mixed[] */
    public function map(callable $callback): array
    {
        return array_values(array_map(
            $callback,
            $this->records,
        ));
    }

    public function count(): int
    {
        return count($this->records);
    }
}
