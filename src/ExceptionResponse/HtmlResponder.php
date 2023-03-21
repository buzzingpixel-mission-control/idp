<?php

declare(strict_types=1);

namespace MissionControlIdp\ExceptionResponse;

use MissionControlBackend\CoreConfig;
use MissionControlBackend\Http\ErrorHandling\HtmlResponse;
use MissionControlBackend\Http\HtmlLayout;
use MissionControlBackend\Templating\TemplatingEngineFactory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

readonly class HtmlResponder implements ExceptionResponder
{
    public function __construct(
        private CoreConfig $config,
        private ResponseFactoryInterface $responseFactory,
        private TemplatingEngineFactory $templatingEngineFactory,
    ) {
    }

    public function respond(Throwable $exception): ResponseInterface
    {
        $templateEngine = $this->templatingEngineFactory->create();

        $templateEngine->setLayout(HtmlLayout::PATH);

        $templateEngine->setView(path: HtmlResponse::PATH);

        $code = $exception->getCode();

        $code = $code > 99 ? $code : 500;

        $response = $this->responseFactory->createResponse($code);

        $templateEngine->addVariable(
            'title',
            'An error occurred',
        );

        $templateEngine->addVariable(
            'content',
            $exception->getMessage(),
        );

        $templateEngine->addVariable(
            'homeUrl',
            $this->config->appUrl,
        );

        $response->getBody()->write($templateEngine->render());

        return $response;
    }
}
