<?php

declare(strict_types=1);

namespace MissionControlIdp\ExceptionResponse;

use MissionControlBackend\Http\IsJsonRequest;
use Psr\Http\Message\ServerRequestInterface;

readonly class ResponderFactory
{
    public function __construct(
        private IsJsonRequest $isJsonRequest,
        private JsonResponder $jsonResponder,
        private HtmlResponder $htmlResponder,
    ) {
    }

    public function create(
        ServerRequestInterface $request,
    ): ExceptionResponder {
        if (
            $this->isJsonRequest->checkHttpAcceptString(
                $request->getServerParams()['HTTP_ACCEPT'] ?? '',
            )
        ) {
            return $this->jsonResponder;
        }

        return $this->htmlResponder;
    }
}
