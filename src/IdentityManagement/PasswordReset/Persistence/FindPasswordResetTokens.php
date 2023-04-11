<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\PasswordReset\Persistence;

use MissionControlIdp\IdentityManagement\Identity;
use MissionControlIdp\IdentityManagement\PasswordReset\PasswordResetToken;
use MissionControlIdp\IdentityManagement\PasswordReset\PasswordResetTokenCollection;
use MissionControlIdp\IdpRedisAdapter;
use Redis;
use ReflectionProperty;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\CacheItem;

use function array_filter;
use function array_map;
use function count;
use function explode;
use function is_string;
use function iterator_to_array;

readonly class FindPasswordResetTokens
{
    private string $redisNamespace;

    public function __construct(
        private Redis $redis,
        private IdpRedisAdapter $storage,
    ) {
        $redisNamespaceProperty = new ReflectionProperty(
            AbstractAdapter::class,
            'namespace',
        );

        /** @noinspection PhpExpressionResultUnusedInspection */
        $redisNamespaceProperty->setAccessible(true);

        $redisNamespace = $redisNamespaceProperty->getValue(
            $storage,
        );

        $redisNamespace = is_string($redisNamespace) ? $redisNamespace : '';

        $this->redisNamespace = $redisNamespace;
    }

    public function findAllByIdentity(
        Identity $identity,
    ): PasswordResetTokenCollection {
        $keys = $this->redis->keys(
            $this->redisNamespace . 'password_reset_token__*',
        );

        if (count($keys) < 1) {
            return new PasswordResetTokenCollection();
        }

        $storageItems = iterator_to_array(
            /** @phpstan-ignore-next-line */
            $this->storage->getItems(array_map(
                static function (string $key): string {
                    $keyArr = explode(':', $key);

                    return $keyArr[count($keyArr) - 1];
                },
                $keys,
            )),
        );

        $items = array_filter(array_map(
            static function (CacheItem $i): PasswordResetToken|null {
                $token = $i->get();

                if (! ($token instanceof PasswordResetToken)) {
                    return null;
                }

                return $token;
            },
            $storageItems,
        ), static fn (PasswordResetToken|null $t) => $t !== null);

        $items = array_filter(
            $items,
            static fn (
                PasswordResetToken $t,
            ) => $t->identityId->isSame($identity->id)
        );

        return new PasswordResetTokenCollection($items);
    }
}
