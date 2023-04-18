<?php

declare(strict_types=1);

namespace MissionControlIdp\Authorize;

use League\OAuth2\Server\Exception\OAuthServerException;
use MissionControlBackend\Http\ApplyRoutesEvent;
use MissionControlIdp\AuthorizationServerFactory;
use MissionControlIdp\ExceptionResponse\ResponderFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

readonly class GetAccessTokenAction
{
    public static function registerRoute(ApplyRoutesEvent $event): void
    {
        $event->post('/oauth2/access-token', self::class);
    }

    public function __construct(
        private ResponderFactory $exceptionResponderFactory,
        private AuthorizationServerFactory $authorizationServerFactory,
    ) {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        try {
            $server = $this->authorizationServerFactory->create();

            return $server->respondToAccessTokenRequest(
                $request,
                $response,
            );
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse($response);
        } catch (Throwable $exception) {
            return $this->exceptionResponderFactory->create(
                $request,
            )->respond($exception);
        }
    }
}
