<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\ValueObjects;

use Funeralzone\ValueObjects\Scalars\StringTrait;
use Funeralzone\ValueObjects\ValueObject;

class Name implements ValueObject
{
    use StringTrait;

    public function isEmpty(): bool
    {
        return $this->toNative() === '';
    }

    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }
}
