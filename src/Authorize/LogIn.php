<?php

declare(strict_types=1);

namespace MissionControlIdp\Authorize;

use HttpSoft\Cookie\CookieManagerInterface;
use MissionControlBackend\Cookies\CookieCreator;
use MissionControlIdp\IdentityManagement\IdentityRepository;
use MissionControlIdp\IdentityManagement\ValidatePassword;
use MissionControlIdp\Session\Session;
use MissionControlIdp\Session\SessionRepository;

readonly class LogIn
{
    public function __construct(
        private CookieCreator $cookieCreator,
        private ValidatePassword $validatePassword,
        private SessionRepository $sessionRepository,
        private CookieManagerInterface $cookieManager,
        private IdentityRepository $identityRepository,
    ) {
    }

    public function withCredentials(Credentials $credentials): LogInResult
    {
        $badLogInResult = new LogInResult(
            false,
            'Unable to log in with those credentials',
        );

        $identity = $this->identityRepository->findOneByEmailAddressOrNull(
            $credentials->email->toNative(),
        );

        if ($identity === null) {
            return $badLogInResult;
        }

        if (
            ! $this->validatePassword->validate(
                $identity,
                $credentials->password->toNative(),
            )
        ) {
            return $badLogInResult;
        }

        $session = $this->sessionRepository->create($identity);

        if ($session === null) {
            return $badLogInResult;
        }

        $sessionCookie = $this->cookieCreator->create(
            Session::COOKIE_NAME,
            $session->sessionKey(),
        );

        $this->cookieManager->set($sessionCookie);

        return new LogInResult(true);
    }
}
