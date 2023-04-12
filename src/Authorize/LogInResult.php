<?php

declare(strict_types=1);

namespace MissionControlIdp\Authorize;

use JetBrains\PhpStorm\ArrayShape;
use MissionControlBackend\Http\JsonResponse\RespondWith;

class LogInResult implements RespondWith
{
    public function __construct(
        public bool $loggedIn,
        public string $message = '',
    ) {
    }

    public function statusCode(): int
    {
        return $this->loggedIn ? 200 : 401;
    }

    /**
     * @return array<string, bool|string>
     *
     * @phpstan-ignore-next-line
     */
    #[ArrayShape([
        'loggedIn' => 'boolean',
        'message' => 'string',
    ])]
    public function asArray(): array
    {
        return [
            'loggedIn' => $this->loggedIn,
            'message' => $this->message,
        ];
    }
}
