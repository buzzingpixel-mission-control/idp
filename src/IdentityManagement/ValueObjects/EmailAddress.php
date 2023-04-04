<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\ValueObjects;

use Funeralzone\ValueObjectExtensions\ComplexScalars\EmailTrait;
use Funeralzone\ValueObjects\ValueObject;

class EmailAddress implements ValueObject
{
    use EmailTrait;
}
