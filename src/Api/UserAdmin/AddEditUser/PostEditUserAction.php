<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\UserAdmin\AddEditUser;

use MissionControlBackend\Http\ApplyRoutesEvent;
use MissionControlBackend\Http\JsonResponse\JsonResponder;
use MissionControlIdp\Api\UserInfo\ActionResultResponseFactory;
use MissionControlIdp\Authorize\RequireAdminMiddleware;
use MissionControlIdp\Authorize\ResourceServerMiddlewareWrapper;
use MissionControlIdp\IdentityManagement\IdentityRepository;
use MissionControlIdp\IdentityManagement\ValueObjects\EmailAddress;
use MissionControlIdp\IdentityManagement\ValueObjects\IsActive;
use MissionControlIdp\IdentityManagement\ValueObjects\IsAdmin;
use MissionControlIdp\IdentityManagement\ValueObjects\Name;
use MissionControlIdp\IdentityManagement\ValueObjects\Timezone;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function is_array;

class PostEditUserAction
{
    public static function registerRoute(ApplyRoutesEvent $event): void
    {
        $event->any('/user-admin/edit-user', self::class)
            ->add(RequireAdminMiddleware::class)
            ->add(ResourceServerMiddlewareWrapper::class);
    }

    public function __construct(
        private JsonResponder $jsonResponder,
        private IdentityRepository $identityRepository,
        private ActionResultResponseFactory $responseFactory,
    ) {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $rawPostData = $request->getParsedBody();

        $postData = PostDataEditUser::fromRawPostData(
            is_array($rawPostData) ? $rawPostData : [],
        );

        $identity = $this->identityRepository->findOneById(
            $postData->userId->toNative(),
        );

        return $this->jsonResponder->respond(
            $this->responseFactory->createResponse(
                $this->identityRepository->saveIdentity(
                    $identity->with(
                        emailAddress: EmailAddress::fromNative(
                            $postData->email->toNative(),
                        ),
                    )->with(name: Name::fromNative(
                        $postData->name->toNative(),
                    ))->with(isAdmin: IsAdmin::fromNative(
                        $postData->isAdmin->toNative(),
                    ))->with(isActive: IsActive::fromNative(
                        $postData->isActive->toNative(),
                    ))->with(timezone: Timezone::fromNative(
                        $postData->timezone->toNative(),
                    )),
                ),
            ),
        );
    }
}
