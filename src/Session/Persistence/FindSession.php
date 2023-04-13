<?php

declare(strict_types=1);

namespace MissionControlIdp\Session\Persistence;

use MissionControlIdp\IdentityManagement\IdentityRepository;
use MissionControlIdp\IdpStoragePool;
use MissionControlIdp\Session\IdentityAndSession;
use MissionControlIdp\Session\Session;
use Throwable;

use function assert;

readonly class FindSession
{
    public function __construct(
        private IdpStoragePool $storagePool,
        private IdentityRepository $identityRepository,
    ) {
    }

    public function findByKey(string $key): IdentityAndSession
    {
        try {
            if ($key === '') {
                return new IdentityAndSession();
            }

            $sessionCacheItem = $this->storagePool->getItem($key);

            if (! $sessionCacheItem->isHit()) {
                return new IdentityAndSession();
            }

            $session = $sessionCacheItem->get();

            assert($session instanceof Session);

            $identity = $this->identityRepository->findOneByIdOrNull(
                $session->identityId->toNative(),
            );

            return new IdentityAndSession(
                $session,
                $identity,
            );
        } catch (Throwable) {
            return new IdentityAndSession();
        }
    }
}
