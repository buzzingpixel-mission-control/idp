<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\UserInfo\EditEmail;

use MissionControlBackend\Http\ApplyRoutesEvent;
use MissionControlBackend\Http\JsonResponse\JsonResponder;
use MissionControlIdp\Api\UserInfo\ActionResultResponseFactory;
use MissionControlIdp\Authorize\ResourceServerMiddlewareWrapper;
use MissionControlIdp\IdentityManagement\IdentityRepository;
use MissionControlIdp\IdentityManagement\ValueObjects\EmailAddress;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use function is_array;

readonly class PostEditEmailAction
{
    public static function registerRoute(ApplyRoutesEvent $event): void
    {
        $event->any('/user-info/edit/email', self::class)
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
        $identity = $this->identityRepository->findOneByRequest(
            $request,
        );

        $rawPostData = $request->getParsedBody();

        $postData = PostData::fromRawPostData(
            is_array($rawPostData) ? $rawPostData : [],
        );

        return $this->jsonResponder->respond(
            $this->responseFactory->createResponse(
                $this->identityRepository->saveIdentity(
                    $identity->with(
                        emailAddress: EmailAddress::fromNative(
                            $postData->email->toNative(),
                        ),
                    ),
                ),
            ),
        );
    }
}
