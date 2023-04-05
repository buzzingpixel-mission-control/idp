<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement;

use function array_map;
use function array_values;

readonly class IdentityCollection
{
    /** @var Identity[] */
    public array $items;

    /** @param Identity[] $items */
    public function __construct(array $items = [])
    {
        $this->items = array_values(array_map(
            static fn (Identity $i) => $i,
            $items,
        ));
    }

    public function first(): Identity
    {
        return $this->items[0];
    }

    public function firstOrNull(): Identity|null
    {
        return $this->items[0] ?? null;
    }

    /** @return mixed[] */
    public function map(callable $callback): array
    {
        return array_values(array_map(
            $callback,
            $this->items,
        ));
    }

    /**
     * @param string[] $without
     *
     * @return array<array-key, array<string, scalar|null>>
     */
    public function asArray(
        array $without = [
            'passwordHash',
            'newPassword',
        ],
    ): array {
        /** @phpstan-ignore-next-line */
        return $this->map(static fn (Identity $u) => $u->asArray(
            $without,
        ));
    }
}
