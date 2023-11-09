<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\Users\QueryParams;

use RuntimeException;

enum AdminStatus
{
    case ALL;
    case IS_ADMIN;
    case IS_NOT_ADMIN;

    public static function fromString(string $status): self
    {
        return match ($status) {
            '', 'all' => self::ALL,
            'is_admin' => self::IS_ADMIN,
            'is_not_admin' => self::IS_NOT_ADMIN,
            default => throw new RuntimeException('Invalid status'),
        };
    }
}
