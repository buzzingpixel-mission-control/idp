<?php

declare(strict_types=1);

namespace MissionControlIdp\AccessToken;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

use function assert;
use function is_string;

class AccessToken implements AccessTokenEntityInterface
{
    use AccessTokenTrait;
    use EntityTrait;
    use TokenEntityTrait;

    public static function compileStoreKeyFromIdentifier(string $id): string
    {
        return 'access_token_' . $id;
    }

    public function getStoreKey(): string
    {
        $id = $this->getIdentifier();

        assert(is_string($id));

        return self::compileStoreKeyFromIdentifier(id: $id);
    }
}
