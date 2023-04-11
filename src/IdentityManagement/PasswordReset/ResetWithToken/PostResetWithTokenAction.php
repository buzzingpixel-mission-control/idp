<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\PasswordReset\ResetWithToken;

use MissionControlBackend\Http\ApplyRoutesEvent;
use MissionControlIdp\IdentityManagement\IdentityRepository;
use MissionControlIdp\IdentityManagement\PasswordReset\PasswordResetTokenRepository;
use MissionControlIdp\IdentityManagement\ValueObjects\Password;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Throwable;

use function implode;
use function is_array;
use function json_encode;

readonly class PostResetWithTokenAction
{
    public function __construct(
        private IdentityRepository $identityRepository,
        private PasswordResetTokenRepository $tokenRepository,
    ) {
    }

    public static function registerRoute(ApplyRoutesEvent $event): void
    {
        $event->post(
            '/password-reset/with-token/{token}',
            self::class,
        );
    }

    /**
     * @param array<string, string> $routeAttributes
     *
     * @throws Throwable
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $routeAttributes,
    ): ResponseInterface {
        $token = (string) ($routeAttributes['token'] ?? '');

        $tokenEntity = $this->tokenRepository->findOneByTokenOrNull(
            $token,
        );

        if ($tokenEntity === null) {
            throw new HttpNotFoundException($request);
        }

        $identity = $this->identityRepository->findOneByIdOrNull(
            $tokenEntity->identityId->toNative(),
        );

        if ($identity === null) {
            throw new HttpNotFoundException($request);
        }

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

        $status = $this->identityRepository->saveIdentity(
            $identity->with(newPassword: Password::fromNative(
                $postData->password,
            )),
        );

        if (! $status->success) {
            $response = $response->withStatus(500);

            $msg = 'An unknown error occurred';

            $response->getBody()->write((string) json_encode([
                'error' => 'validation',
                'error_description' => $msg,
                'message' => $msg,
            ]));

            return $response;
        }

        $this->tokenRepository->delete($tokenEntity);

        $response->getBody()->write(
            (string) json_encode(['success' => true]),
        );

        return $response;
    }
}
