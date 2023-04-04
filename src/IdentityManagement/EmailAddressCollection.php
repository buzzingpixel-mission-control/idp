<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement;

use Spatie\Cloneable\Cloneable;

use function array_map;
use function array_values;
use function count;

class EmailAddressCollection
{
    use Cloneable;

    /** @var EmailAddress[] */
    public array $addresses;

    /** @param EmailAddress[] $addresses */
    public function __construct(array $addresses = [])
    {
        $this->addresses = array_values(array_map(
            static fn (EmailAddress $e) => $e,
            $addresses,
        ));
    }

    public function count(): int
    {
        return count($this->addresses);
    }

    /** @return mixed[] */
    public function map(callable $callback): array
    {
        return array_values(array_map(
            $callback,
            $this->addresses,
        ));
    }

    /** @return string[] */
    public function toStringArray(): array
    {
        /** @phpstan-ignore-next-line */
        return $this->map(
            static fn (EmailAddress $e) => $e->emailAddress,
        );
    }
}
