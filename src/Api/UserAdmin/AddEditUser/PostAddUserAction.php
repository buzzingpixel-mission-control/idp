<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\UserAdmin\AddEditUser;

use MissionControlBackend\Http\ApplyRoutesEvent;
use MissionControlBackend\Http\JsonResponse\JsonResponder;
use MissionControlIdp\Api\UserInfo\ActionResultResponseFactory;
use MissionControlIdp\Authorize\RequireAdminMiddleware;
use MissionControlIdp\Authorize\ResourceServerMiddlewareWrapper;
use MissionControlIdp\IdentityManagement\IdentityRepository;
use MissionControlIdp\IdentityManagement\NewIdentity;
use MissionControlIdp\IdentityManagement\ValueObjects\EmailAddress;
use MissionControlIdp\IdentityManagement\ValueObjects\IsAdmin;
use MissionControlIdp\IdentityManagement\ValueObjects\Name;
use MissionControlIdp\IdentityManagement\ValueObjects\Password;
use MissionControlIdp\IdentityManagement\ValueObjects\Timezone;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function is_array;

readonly class PostAddUserAction
{
    public static function registerRoute(ApplyRoutesEvent $event): void
    {
        $event->any('/user-admin/add-user', self::class)
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

        $postData = PostDataAddUser::fromRawPostData(
            is_array($rawPostData) ? $rawPostData : [],
        );

        return $this->jsonResponder->respond(
            $this->responseFactory->createResponse(
                $this->identityRepository->createIdentity(
                    new NewIdentity(
                        EmailAddress::fromNative(
                            $postData->email->toNative(),
                        ),
                        IsAdmin::fromNative(
                            $postData->isAdmin->toNative(),
                        ),
                        Name::fromNative(
                            $postData->name->toNative(),
                        ),
                        Password::fromNative(
                            $postData->password->toNative(),
                        ),
                        Timezone::fromNative(
                            $postData->timezone->toNative(),
                        ),
                    ),
                ),
            ),
        );
    }
}
