<?php

declare(strict_types=1);

namespace MissionControlIdp\Session\ValueObjects;

use Funeralzone\ValueObjectExtensions\ComplexScalars\UUIDTrait;
use Funeralzone\ValueObjects\ValueObject;

class IdentityId implements ValueObject
{
    use UUIDTrait;
}
