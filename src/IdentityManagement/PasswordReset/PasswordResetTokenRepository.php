<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\PasswordReset;

use DateInterval;
use MissionControlIdp\IdentityManagement\ActionResult;
use MissionControlIdp\IdentityManagement\Identity;
use MissionControlIdp\IdentityManagement\PasswordReset\Persistence\CreatePasswordResetToken;
use MissionControlIdp\IdentityManagement\PasswordReset\Persistence\DeletePasswordResetToken;
use MissionControlIdp\IdentityManagement\PasswordReset\Persistence\FindPasswordResetTokens;

readonly class PasswordResetTokenRepository
{
    public function __construct(
        private FindPasswordResetTokens $findPasswordResetTokens,
        private CreatePasswordResetToken $createPasswordResetToken,
        private DeletePasswordResetToken $deletePasswordResetToken,
    ) {
    }

    public function createToken(
        Identity $identity,
        int $maxTokens = 5,
        DateInterval $expiresAfter = new DateInterval('PT2H'),
    ): PasswordResetToken|false {
        return $this->createPasswordResetToken->create(
            $identity,
            $maxTokens,
            $expiresAfter,
        );
    }

    public function findAllByIdentity(
        Identity $identity,
    ): PasswordResetTokenCollection {
        return $this->findPasswordResetTokens->findAllByIdentity(
            $identity,
        );
    }

    public function findOneByTokenOrNull(string $token): PasswordResetToken|null
    {
        return $this->findPasswordResetTokens->findOneByTokenOrNull(
            $token,
        );
    }

    public function delete(PasswordResetToken $token): ActionResult
    {
        return $this->deletePasswordResetToken->delete($token);
    }
}
