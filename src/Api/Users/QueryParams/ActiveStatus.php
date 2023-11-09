<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\Users\QueryParams;

use RuntimeException;

enum ActiveStatus
{
    case ALL;
    case ACTIVE;
    case INACTIVE;

    public static function fromString(string $status): self
    {
        return match ($status) {
            '', 'all' => self::ALL,
            'active' => self::ACTIVE,
            'inactive' => self::INACTIVE,
            default => throw new RuntimeException('Invalid status'),
        };
    }
}
