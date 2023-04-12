<?php

declare(strict_types=1);

namespace MissionControlIdp\Authorize;

use MissionControlBackend\Http\JsonResponse\RespondWith;
use MissionControlBackend\Http\JsonResponse\RespondWithArrayAndStatus;
use MissionControlIdp\Authorize\ValueObjects\EmailAddress;

readonly class PostLogInAndRespondWithFactory
{
    public function __construct(private LogIn $logIn)
    {
    }

    /** @param scalar[] $rawPostData */
    public function logInAndRespondWith(array $rawPostData): RespondWith
    {
        $email = (string) ($rawPostData['email'] ?? '');

        $emailValidation = EmailAddress::nativeIsValid($email);

        if (! $emailValidation->success) {
            return new RespondWithArrayAndStatus(
                ['message' => $emailValidation->message[0]],
                400,
            );
        }

        $credentials = Credentials::fromArray($rawPostData);

        return $this->logIn->withCredentials($credentials);
    }
}
