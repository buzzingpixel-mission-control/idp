<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement;

use Funeralzone\ValueObjectExtensions\ComplexScalars\EmailTrait;
use Funeralzone\ValueObjects\ValueObject;

class EmailAddress implements ValueObject
{
    use EmailTrait;
}
