<?php

declare(strict_types=1);

namespace MissionControlIdp\RefreshToken;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use MissionControlIdp\IdpStoragePool;
use RuntimeException;

use function assert;

readonly class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    public function __construct(private IdpStoragePool $storagePool)
    {
    }

    public function getNewRefreshToken(): RefreshTokenEntityInterface
    {
        return new RefreshToken();
    }

    public function persistNewRefreshToken(
        RefreshTokenEntityInterface $refreshTokenEntity,
    ): void {
        assert($refreshTokenEntity instanceof RefreshToken);

        $success = $this->storagePool->save(
            $this->storagePool
                ->getItem($refreshTokenEntity->getStoreKey())
                ->set($refreshTokenEntity)
                ->expiresAt($refreshTokenEntity->getExpiryDateTime()),
        );

        if ($success) {
            return;
        }

        throw new RuntimeException('Unable to persist refresh token');
    }

    /** @inheritDoc */
    public function revokeRefreshToken($tokenId): void
    {
        $success = $this->storagePool->deleteItem(
            RefreshToken::compileStoreKeyFromIdentifier($tokenId),
        );

        if ($success) {
            return;
        }

        throw new RuntimeException('Unable to remove refresh token');
    }

    /** @inheritDoc */
    public function isRefreshTokenRevoked($tokenId): bool
    {
        return ! $this->storagePool->getItem(
            RefreshToken::compileStoreKeyFromIdentifier($tokenId),
        )->isHit();
    }
}
