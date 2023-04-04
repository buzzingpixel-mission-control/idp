<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement;

readonly class ActionResult
{
    /** @param string[] $message */
    public function __construct(
        public bool $success = true,
        public array $message = [],
        public int|string $errorCode = '',
    ) {
    }
}
