<?php

declare(strict_types=1);

namespace MissionControlIdp\RefreshToken;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\RefreshTokenTrait;

use function assert;
use function is_string;

class RefreshToken implements RefreshTokenEntityInterface
{
    use RefreshTokenTrait;
    use EntityTrait;

    public static function compileStoreKeyFromIdentifier(string $id): string
    {
        return 'refresh_token_' . $id;
    }

    public function getStoreKey(): string
    {
        $id = $this->getIdentifier();

        assert(is_string($id));

        return self::compileStoreKeyFromIdentifier(id: $id);
    }
}
