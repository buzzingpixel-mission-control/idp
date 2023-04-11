<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\PasswordReset\ResetWithToken;

use BuzzingPixel\Templating\TemplateEngineFactory;
use MissionControlBackend\Http\ApplyRoutesEvent;
use MissionControlIdp\IdentityManagement\IdentityRepository;
use MissionControlIdp\IdentityManagement\PasswordReset\PasswordResetTokenRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Throwable;

readonly class GetResetWithTokenAction
{
    public function __construct(
        private IdentityRepository $identityRepository,
        private TemplateEngineFactory $templateEngineFactory,
        private PasswordResetTokenRepository $tokenRepository,
    ) {
    }

    public static function registerRoute(ApplyRoutesEvent $event): void
    {
        $event->get(
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

        $templateEngine = $this->templateEngineFactory->create()
            ->templatePath(
                __DIR__ . '/GetResetWithTokenAction.phtml',
            )
            ->addVar('emailAddress', $identity->emailAddress->toNative());

        $response->getBody()->write($templateEngine->render());

        return $response;
    }
}
