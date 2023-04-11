<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\PasswordReset\Persistence;

use DateInterval;
use MissionControlBackend\Persistence\UuidFactoryWithOrderedTimeCodec;
use MissionControlIdp\IdentityManagement\Identity;
use MissionControlIdp\IdentityManagement\PasswordReset\PasswordResetToken;
use MissionControlIdp\IdentityManagement\PasswordReset\ValueObjects\CreatedAt;
use MissionControlIdp\IdentityManagement\PasswordReset\ValueObjects\Id;
use MissionControlIdp\IdentityManagement\PasswordReset\ValueObjects\IdentityId;
use MissionControlIdp\IdentityManagement\PasswordReset\ValueObjects\Token;
use MissionControlIdp\IdpRedisAdapter;
use MissionControlIdp\Security\UrlSafeRandomTokenGenerator;
use Psr\Clock\ClockInterface;

readonly class CreatePasswordResetToken
{
    public function __construct(
        private ClockInterface $clock,
        private IdpRedisAdapter $storage,
        private UrlSafeRandomTokenGenerator $tokenGenerator,
        private UuidFactoryWithOrderedTimeCodec $uuidFactory,
        private FindPasswordResetTokens $findPasswordResetTokens,
    ) {
    }

    public function create(
        Identity $identity,
        int $maxTokens = 5,
        DateInterval $expiresAfter = new DateInterval('PT2H'),
    ): PasswordResetToken|false {
        if (
            ! $this->validateMaxTokens(
                $identity,
                $maxTokens,
            )
        ) {
            return false;
        }

        $token = new PasswordResetToken(
            new Id($this->uuidFactory->uuid4()),
            IdentityId::fromNative($identity->id->toNative()),
            Token::fromNative($this->tokenGenerator->generate()),
            new CreatedAt($this->clock->now()),
        );

        $success = $this->storage->save($this->storage->getItem(
            $token->key(),
        )->set($token)->expiresAfter($expiresAfter));

        if ($success === false) {
            return false;
        }

        return $token;
    }

    private function validateMaxTokens(
        Identity $identity,
        int $maxTokens = 5,
    ): bool {
        $existingTokens = $this->findPasswordResetTokens->findAllByIdentity(
            $identity,
        );

        return $existingTokens->count() < $maxTokens;
    }
}
