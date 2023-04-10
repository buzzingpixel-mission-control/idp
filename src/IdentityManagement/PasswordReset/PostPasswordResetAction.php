<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\PasswordReset;

use MissionControlBackend\Http\ApplyRoutesEvent;
use MissionControlIdp\IdentityManagement\IdentityRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function implode;
use function is_array;
use function json_encode;

readonly class PostPasswordResetAction
{
    public static function registerRoute(ApplyRoutesEvent $event): void
    {
        $event->post('/password-reset', self::class);
    }

    public function __construct(
        private IdentityRepository $identityRepository,
        private SendPasswordResetEmail $sendPasswordResetEmail,
    ) {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $rawData = $request->getParsedBody();

        $postData = PostData::fromArray(
            is_array($rawData) ? $rawData : [],
        );

        if ($postData->hasErrors) {
            $response = $response->withStatus(400);

            $msg = implode('. ', $postData->errors) . '.';

            $response->getBody()->write((string) json_encode([
                'error' => 'validation',
                'error_description' => $msg,
                'message' => $msg,
            ]));

            return $response;
        }

        $response->getBody()->write((string) json_encode([]));

        $identity = $this->identityRepository->findOneByEmailAddressOrNull(
            $postData->email,
        );

        if ($identity === null) {
            return $response;
        }

        $this->sendPasswordResetEmail->sendForIdentity($identity);

        return $response;
    }
}
