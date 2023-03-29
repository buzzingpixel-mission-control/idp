<?php

declare(strict_types=1);

namespace MissionControlIdp\Authorize;

use BuzzingPixel\Templating\TemplateEngineFactory;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

readonly class LogInResponder implements Responder
{
    public function __construct(
        private AuthorizationServer $authorizationServer,
        private TemplateEngineFactory $templateEngineFactory,
    ) {
    }

    /** @throws OAuthServerException */
    public function respond(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $this->authorizationServer->validateAuthorizationRequest(
            $request,
        );

        $templateEngine = $this->templateEngineFactory->create()
            ->templatePath(__DIR__ . '/LogInResponse.phtml');

        $response->getBody()->write($templateEngine->render());

        return $response;
    }
}
