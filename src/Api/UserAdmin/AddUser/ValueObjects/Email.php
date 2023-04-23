<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\UserAdmin\AddUser\ValueObjects;

use Funeralzone\ValueObjectExtensions\ComplexScalars\EmailTrait;
use Funeralzone\ValueObjects\ValueObject;

class Email implements ValueObject
{
    use EmailTrait;
}
