<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\ValueObjects;

use Funeralzone\ValueObjectExtensions\ComplexScalars\EmailTrait;
use Funeralzone\ValueObjects\ValueObject;
use InvalidArgumentException;
use MissionControlIdp\IdentityManagement\ActionResult;

class EmailAddress implements ValueObject
{
    use EmailTrait;

    public static function nativeIsValid(string $native): ActionResult
    {
        try {
            self::fromNative($native);

            return new ActionResult();
        } catch (InvalidArgumentException $exception) {
            return new ActionResult(
                false,
                [$exception->getMessage()],
            );
        }
    }
}
