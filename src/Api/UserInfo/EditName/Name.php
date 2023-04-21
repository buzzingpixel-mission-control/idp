<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\UserInfo\EditName;

use Assert\Assert;
use Funeralzone\ValueObjects\Scalars\StringTrait;
use Funeralzone\ValueObjects\ValueObject;

class Name implements ValueObject
{
    use StringTrait;

    public function __construct(string $string)
    {
        Assert::that($string)->notEmpty('Name is required');

        $this->string = $string;
    }
}
