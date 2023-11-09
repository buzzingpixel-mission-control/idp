<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\Users;

use MissionControlBackend\Http\ApplyRoutesEvent;
use MissionControlIdp\Api\Users\QueryParams\FilterParams;
use MissionControlIdp\Authorize\ResourceServerMiddlewareWrapper;
use MissionControlIdp\IdentityManagement\IdentityRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function json_encode;

use const JSON_PRETTY_PRINT;

readonly class GetUsersListAction
{
    public static function registerRoute(ApplyRoutesEvent $event): void
    {
        $event->any('/users/list', self::class)
            ->add(ResourceServerMiddlewareWrapper::class);
    }

    public function __construct(
        private IdentityRepository $identityRepository,
        private FindIdentityParametersFactory $findIdentityParametersFactory,
    ) {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $users = $this->identityRepository->findAll(
            $this->findIdentityParametersFactory->create(
                FilterParams::fromRawPostData(
                    $request->getQueryParams(),
                ),
            ),
        );

        $response = $response->withHeader(
            'Content-type',
            'application/json',
        );

        $response->getBody()->write((string) json_encode(
            $users->asArray(),
            JSON_PRETTY_PRINT,
        ));

        return $response;
    }
}
