<?php

declare(strict_types=1);

namespace MissionControlIdp\AccessToken;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use MissionControlIdp\IdpStoragePool;
use RuntimeException;

use function array_walk;
use function assert;
use function is_int;
use function is_string;

readonly class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    public function __construct(private IdpStoragePool $storagePool)
    {
    }

    /** @inheritDoc */
    public function getNewToken(
        ClientEntityInterface $clientEntity,
        array $scopes,
        $userIdentifier = null,
    ): AccessTokenEntityInterface {
        $accessToken = new AccessToken();

        array_walk(
            $scopes,
            [$accessToken, 'addScope'],
        );

        if (
            is_string($userIdentifier) ||
            is_int($userIdentifier)
        ) {
            $accessToken->setUserIdentifier($userIdentifier);
        }

        return $accessToken;
    }

    public function persistNewAccessToken(
        AccessTokenEntityInterface $accessTokenEntity,
    ): void {
        assert($accessTokenEntity instanceof AccessToken);

        $success = $this->storagePool->save(
            $this->storagePool
                ->getItem($accessTokenEntity->getStoreKey())
                ->set($accessTokenEntity)
                ->expiresAt($accessTokenEntity->getExpiryDateTime()),
        );

        if ($success) {
            return;
        }

        throw new RuntimeException('Unable to persist access token');
    }

    /** @inheritDoc */
    public function revokeAccessToken($tokenId): void
    {
        $success = $this->storagePool->deleteItem(
            AccessToken::compileStoreKeyFromIdentifier($tokenId),
        );

        if ($success) {
            return;
        }

        throw new RuntimeException('Unable to remove access token');
    }

    /** @inheritDoc */
    public function isAccessTokenRevoked($tokenId): bool
    {
        return ! $this->storagePool->getItem(
            AccessToken::compileStoreKeyFromIdentifier($tokenId),
        )->isHit();
    }
}
