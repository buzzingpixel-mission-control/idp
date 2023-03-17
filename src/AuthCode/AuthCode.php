<?php

declare(strict_types=1);

namespace MissionControlIdp\AuthCode;

use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\Traits\AuthCodeTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

use function assert;
use function is_string;

class AuthCode implements AuthCodeEntityInterface
{
    use AuthCodeTrait;
    use EntityTrait;
    use TokenEntityTrait;

    public static function compileStoreKeyFromIdentifier(string $id): string
    {
        return 'auth_code_' . $id;
    }

    public function getStoreKey(): string
    {
        $id = $this->getIdentifier();

        assert(is_string($id));

        return self::compileStoreKeyFromIdentifier(id: $id);
    }
}
