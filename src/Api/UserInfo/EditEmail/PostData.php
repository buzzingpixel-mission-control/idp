<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\UserInfo\EditEmail;

class PostData
{
    /** @param string[] $rawPostData */
    public static function fromRawPostData(array $rawPostData): self
    {
        return new self(new Email($rawPostData['value'] ?? ''));
    }

    public function __construct(public Email $email)
    {
    }
}
