<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\PasswordReset;

use BuzzingPixel\Templating\TemplateEngineFactory;
use MissionControlBackend\Mailer\EmailBuilderFactory;
use MissionControlBackend\Mailer\QueueMailer;
use MissionControlBackend\Url\AuthUrlGenerator;
use MissionControlIdp\IdentityManagement\ActionResult;
use MissionControlIdp\IdentityManagement\Identity;
use Symfony\Component\Mime\Address;

use function trim;

readonly class SendPasswordResetEmail
{
    public function __construct(
        private QueueMailer $queueMailer,
        private EmailBuilderFactory $emailBuilderFactory,
        private AuthUrlGenerator $authUrlGenerator,
        private TemplateEngineFactory $templateEngineFactory,
        private PasswordResetTokenRepository $tokenRepository,
    ) {
    }

    public function sendForIdentity(Identity $identity): ActionResult
    {
        $token = $this->tokenRepository->createToken($identity);

        if ($token === false) {
            return new ActionResult(false);
        }

        $resetLink = $this->authUrlGenerator->generate(
            'password-reset/with-token/' . $token->token->toNative(),
        );

        $template = $this->templateEngineFactory->create()
            ->templatePath(
                __DIR__ . '/SendPasswordResetEmail.phtml',
            )
            ->addVar('resetLink', $resetLink)
            ->render();

        $this->queueMailer->send(
            $this->emailBuilderFactory->create()
                ->to(new Address(
                    $identity->emailAddress->toNative(),
                    $identity->name->toNative(),
                ))
                ->subject('Reset your Mission Control password')
                ->text(trim(
                    $template,
                ))
                ->getEmail(),
        );

        return new ActionResult();
    }
}
