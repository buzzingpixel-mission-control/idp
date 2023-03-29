<?php

declare(strict_types=1);

namespace MissionControlIdp\ExceptionResponse;

use BuzzingPixel\Templating\TemplateEngineFactory;
use MissionControlBackend\CoreConfig;
use MissionControlBackend\Http\ErrorHandling\HtmlResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

readonly class HtmlResponder implements ExceptionResponder
{
    public function __construct(
        private CoreConfig $config,
        private ResponseFactoryInterface $responseFactory,
        private TemplateEngineFactory $templateEngineFactory,
    ) {
    }

    public function respond(Throwable $exception): ResponseInterface
    {
        $code = $exception->getCode();

        $code = $code > 99 ? $code : 500;

        $response = $this->responseFactory->createResponse($code);

        $templateEngine = $this->templateEngineFactory->create()
            ->templatePath(HtmlResponse::PATH)
            ->addVar('title', 'An error occurred')
            ->addVar('content', $exception->getMessage())
            ->addVar('homeUrl', $this->config->appUrl);

        $response->getBody()->write($templateEngine->render());

        return $response;
    }
}
