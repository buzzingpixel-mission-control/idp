<?php

declare(strict_types=1);

namespace MissionControlIdp\Session;

use DateInterval;
use MissionControlBackend\Persistence\UuidFactoryWithOrderedTimeCodec;
use MissionControlIdp\IdentityManagement\Identity;
use MissionControlIdp\Session\Persistence\PersistSession;
use MissionControlIdp\Session\ValueObjects\CreatedAt;
use MissionControlIdp\Session\ValueObjects\Id;
use MissionControlIdp\Session\ValueObjects\IdentityId;
use Psr\Clock\ClockInterface;

readonly class SessionRepository
{
    public function __construct(
        private ClockInterface $clock,
        private PersistSession $persistSession,
        private UuidFactoryWithOrderedTimeCodec $uuidFactory,
        private DateInterval $sessionExpiresAfter = new DateInterval('P30D'),
    ) {
    }

    public function create(Identity $identity): Session|null
    {
        $session = new Session(
            new Id($this->uuidFactory->uuid4()),
            IdentityId::fromNative($identity->id->toNative()),
            new CreatedAt($this->clock->now()),
        );

        if (
            ! $this->persistSession->persist(
                $session,
                $this->sessionExpiresAfter,
            )->success
        ) {
            return null;
        }

        return $session;
    }
}
