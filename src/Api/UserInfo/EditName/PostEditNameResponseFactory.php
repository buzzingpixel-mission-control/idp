<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\UserInfo\EditName;

use MissionControlBackend\Http\JsonResponse\RespondWith;
use MissionControlBackend\Http\JsonResponse\RespondWithArrayAndStatus;
use MissionControlIdp\IdentityManagement\ActionResult;

use function implode;

class PostEditNameResponseFactory
{
    public function createResponse(ActionResult $result): RespondWith
    {
        if ($result->success) {
            return new RespondWithArrayAndStatus();
        }

        return new RespondWithArrayAndStatus(
            [
                'success' => false,
                'message' => implode('. ', $result->message),
            ],
            500,
        );
    }
}
