<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\PasswordReset;

use function array_map;
use function array_values;
use function count;

readonly class PasswordResetTokenCollection
{
    /** @var PasswordResetToken[] */
    public array $items;

    /** @param PasswordResetToken[] $items */
    public function __construct(array $items = [])
    {
        $this->items = array_values(array_map(
            static fn (PasswordResetToken $i) => $i,
            $items,
        ));
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function first(): PasswordResetToken
    {
        return $this->items[0];
    }

    public function firstOrNull(): PasswordResetToken|null
    {
        return $this->items[0] ?? null;
    }
}
