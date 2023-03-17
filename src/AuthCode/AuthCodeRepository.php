<?php

declare(strict_types=1);

namespace MissionControlIdp\AuthCode;

use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use MissionControlIdp\IdpStoragePool;
use RuntimeException;

use function assert;

readonly class AuthCodeRepository implements AuthCodeRepositoryInterface
{
    public function __construct(private IdpStoragePool $storagePool)
    {
    }

    public function getNewAuthCode(): AuthCodeEntityInterface
    {
        return new AuthCode();
    }

    public function persistNewAuthCode(
        AuthCodeEntityInterface $authCodeEntity,
    ): void {
        assert($authCodeEntity instanceof AuthCode);

        $success = $this->storagePool->save(
            $this->storagePool
                ->getItem($authCodeEntity->getStoreKey())
                ->set($authCodeEntity)
                ->expiresAt($authCodeEntity->getExpiryDateTime()),
        );

        if ($success) {
            return;
        }

        throw new RuntimeException('Unable to persist auth code');
    }

    /** @inheritDoc */
    public function revokeAuthCode($codeId): void
    {
        $success = $this->storagePool->deleteItem(
            AuthCode::compileStoreKeyFromIdentifier($codeId),
        );

        if ($success) {
            return;
        }

        throw new RuntimeException('Unable to remove auth code');
    }

    /** @inheritDoc */
    public function isAuthCodeRevoked($codeId): bool
    {
        return ! $this->storagePool->getItem(
            AuthCode::compileStoreKeyFromIdentifier($codeId),
        )->isHit();
    }
}
