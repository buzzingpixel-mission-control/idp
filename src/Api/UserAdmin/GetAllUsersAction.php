<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\UserAdmin;

use MissionControlBackend\Http\ApplyRoutesEvent;
use MissionControlBackend\Persistence\StringCollection;
use MissionControlIdp\Authorize\RequireAdminMiddleware;
use MissionControlIdp\Authorize\ResourceServerMiddlewareWrapper;
use MissionControlIdp\IdentityManagement\IdentityRepository;
use MissionControlIdp\IdentityManagement\Persistence\FindIdentityParameters;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function json_encode;

use const JSON_PRETTY_PRINT;

readonly class GetAllUsersAction
{
    public static function registerRoute(ApplyRoutesEvent $event): void
    {
        $event->any('/user-admin/all-users', self::class)
            ->add(RequireAdminMiddleware::class)
            ->add(ResourceServerMiddlewareWrapper::class);
    }

    public function __construct(private IdentityRepository $identityRepository)
    {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $loggedInIdentity = $this->identityRepository->findOneByRequest(
            $request,
        );

        $identities = $this->identityRepository->findAll(
            new FindIdentityParameters(
                notIds: new StringCollection([
                    $loggedInIdentity->getIdentifier(),
                ]),
            ),
        );

        $response = $response->withHeader(
            'Content-type',
            'application/json',
        );

        $response->getBody()->write((string) json_encode(
            $identities->asArray(),
            JSON_PRETTY_PRINT,
        ));

        return $response;
    }
}
