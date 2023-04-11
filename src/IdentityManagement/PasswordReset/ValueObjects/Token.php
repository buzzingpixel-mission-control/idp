<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\PasswordReset\ValueObjects;

use Funeralzone\ValueObjects\Scalars\StringTrait;
use Funeralzone\ValueObjects\ValueObject;

class Token implements ValueObject
{
    use StringTrait;
}
