<?php

declare(strict_types=1);

namespace MissionControlIdp;

use DateInterval;
use Spatie\Cloneable\Cloneable;

readonly class AuthorizationServerFactoryConfig
{
    use Cloneable;

    public function __construct(
        public string $encryptionKey,
        public string $privateKeyPath,
        public string $publicKeyPath,
        public DateInterval $authCodeExpiration,
        public DateInterval $refreshTokenExpiration,
        public DateInterval $accessTokenExpiration,
    ) {
    }
}
