<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\UserInfo\EditName;

readonly class PostData
{
    /** @param string[] $rawPostData */
    public static function fromRawPostData(array $rawPostData): self
    {
        return new self(new Name($rawPostData['value'] ?? ''));
    }

    public function __construct(public Name $name)
    {
    }
}
