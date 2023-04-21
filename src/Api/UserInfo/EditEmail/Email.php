<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\UserInfo\EditEmail;

use Funeralzone\ValueObjectExtensions\ComplexScalars\EmailTrait;
use Funeralzone\ValueObjects\ValueObject;

class Email implements ValueObject
{
    use EmailTrait;
}
