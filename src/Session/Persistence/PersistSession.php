<?php

declare(strict_types=1);

namespace MissionControlIdp\Session\Persistence;

use DateInterval;
use MissionControlIdp\IdentityManagement\ActionResult;
use MissionControlIdp\IdpStoragePool;
use MissionControlIdp\Session\Session;

readonly class PersistSession
{
    public function __construct(private IdpStoragePool $storagePool)
    {
    }

    public function persist(
        Session $session,
        DateInterval $expiresAfter,
    ): ActionResult {
        return new ActionResult(
            $this->storagePool->save(
                $this->storagePool->getItem($session->sessionKey())
                ->set($session)
                ->expiresAfter($expiresAfter),
            ),
        );
    }
}
