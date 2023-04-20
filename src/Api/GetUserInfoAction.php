<?php

declare(strict_types=1);

namespace MissionControlIdp\Api;

use MissionControlBackend\Http\ApplyRoutesEvent;
use MissionControlIdp\Authorize\ResourceServerMiddlewareWrapper;
use MissionControlIdp\IdentityManagement\IdentityRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function json_encode;

use const JSON_PRETTY_PRINT;

readonly class GetUserInfoAction
{
    public static function registerRoute(ApplyRoutesEvent $event): void
    {
        $event->any('/user-info', self::class)
            ->add(ResourceServerMiddlewareWrapper::class);
    }

    public function __construct(private IdentityRepository $identityRepository)
    {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $identity = $this->identityRepository->findOneByRequest(
            $request,
        );

        $response = $response->withHeader(
            'Content-type',
            'application/json',
        );

        $response->getBody()->write((string) json_encode(
            [
                'emailAddress' => $identity->emailAddress->toNative(),
                'name' => $identity->nameOrEmail(),
                'isAdmin' => $identity->isAdmin->toNative(),
            ],
            JSON_PRETTY_PRINT,
        ));

        return $response;
    }
}
