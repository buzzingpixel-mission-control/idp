<?php

declare(strict_types=1);

namespace MissionControlIdp\Authorize;

use BuzzingPixel\Templating\TemplateEngineFactory;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use MissionControlBackend\Csrf\CsrfTokenGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

readonly class LogInResponder implements Responder
{
    public function __construct(
        private CsrfTokenGenerator $csrfTokenGenerator,
        private AuthorizationServer $authorizationServer,
        private TemplateEngineFactory $templateEngineFactory,
    ) {
    }

    /** @throws OAuthServerException */
    public function respond(
        ServerRequestInterface $request,
        ResponseInterface $response,
        AuthorizationRequest $authRequest,
    ): ResponseInterface {
        $this->authorizationServer->validateAuthorizationRequest(
            $request,
        );

        $templateEngine = $this->templateEngineFactory->create()
            ->templatePath(__DIR__ . '/LogInResponse.phtml')
            ->addVar('csrfToken', $this->csrfTokenGenerator->generate());

        $response->getBody()->write($templateEngine->render());

        return $response;
    }
}
