<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\UserInfo\EditName;

use MissionControlBackend\Http\ApplyRoutesEvent;
use MissionControlBackend\Http\JsonResponse\JsonResponder;
use MissionControlIdp\Authorize\ResourceServerMiddlewareWrapper;
use MissionControlIdp\IdentityManagement\IdentityRepository;
use MissionControlIdp\IdentityManagement\ValueObjects\Name;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function is_array;

readonly class PostEditNameAction
{
    public static function registerRoute(ApplyRoutesEvent $event): void
    {
        $event->any('/user-info/edit/name', self::class)
            ->add(ResourceServerMiddlewareWrapper::class);
    }

    public function __construct(
        private JsonResponder $jsonResponder,
        private IdentityRepository $identityRepository,
        private PostEditNameResponseFactory $postEditNameResponseFactory,
    ) {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $identity = $this->identityRepository->findOneByRequest(
            $request,
        );

        $rawPostData = $request->getParsedBody();

        $postData = PostData::fromRawPostData(
            is_array($rawPostData) ? $rawPostData : [],
        );

        return $this->jsonResponder->respond(
            $this->postEditNameResponseFactory->createResponse(
                $this->identityRepository->saveIdentity(
                    $identity->with(name: Name::fromNative(
                        $postData->name->toNative(),
                    )),
                ),
            ),
        );
    }
}
