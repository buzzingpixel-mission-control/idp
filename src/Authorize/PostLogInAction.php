<?php

declare(strict_types=1);

namespace MissionControlIdp\Authorize;

use MissionControlBackend\Http\ApplyRoutesEvent;
use MissionControlBackend\Http\JsonResponse\JsonResponder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Csrf\Guard as CsrfGuard;

use function is_array;

readonly class PostLogInAction
{
    public static function registerRoute(ApplyRoutesEvent $event): void
    {
        $event->post('/log-in', self::class)->add(
            CsrfGuard::class,
        );
    }

    public function __construct(
        private JsonResponder $responder,
        private PostLogInAndRespondWithFactory $respondWithFactory,
    ) {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $postData = $request->getParsedBody();
        $postData = is_array($postData) ? $postData : [];

        return $this->responder->respond(
            $this->respondWithFactory->logInAndRespondWith(
                $postData,
            ),
        );
    }
}
