<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\UserInfo\EditPassword;

use Assert\Assert;
use Funeralzone\ValueObjects\Scalars\StringTrait;

class Password
{
    use StringTrait;

    public function __construct(string $string)
    {
        Assert::that($string)->notEmpty(
            'Password is required',
        );

        $this->string = $string;
    }
}
