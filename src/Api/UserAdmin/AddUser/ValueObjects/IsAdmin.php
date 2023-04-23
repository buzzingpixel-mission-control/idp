<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\UserAdmin\AddUser\ValueObjects;

use Funeralzone\ValueObjects\Scalars\BooleanTrait;
use Funeralzone\ValueObjects\ValueObject;

class IsAdmin implements ValueObject
{
    use BooleanTrait;

    public static function fromString(string $value): self
    {
        return new self((bool) $value);
    }
}
