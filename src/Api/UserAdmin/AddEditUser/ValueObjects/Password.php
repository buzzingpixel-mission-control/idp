<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\UserAdmin\AddEditUser\ValueObjects;

use Funeralzone\ValueObjects\Scalars\StringTrait;
use Funeralzone\ValueObjects\ValueObject;

class Password implements ValueObject
{
    use StringTrait;
}
