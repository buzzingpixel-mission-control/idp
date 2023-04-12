<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\PasswordReset;

use BuzzingPixel\Templating\TemplateEngineFactory;
use MissionControlBackend\Csrf\CsrfTokenGenerator;
use MissionControlBackend\Http\ApplyRoutesEvent;
use MissionControlIdp\ErrorIfAuthAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

readonly class GetPasswordResetAction
{
    public static function registerRoute(ApplyRoutesEvent $event): void
    {
        $event->get(
            '/password-reset',
            self::class,
        )->add(ErrorIfAuthAction::class);
    }

    public function __construct(
        private CsrfTokenGenerator $csrfTokenGenerator,
        private TemplateEngineFactory $templateEngineFactory,
    ) {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $templateEngine = $this->templateEngineFactory->create()
            ->templatePath(__DIR__ . '/GetPasswordReset.phtml')
            ->addVar('csrfToken', $this->csrfTokenGenerator->generate());

        $response->getBody()->write($templateEngine->render());

        return $response;
    }
}
