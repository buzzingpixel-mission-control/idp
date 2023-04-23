<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\UserInfo\EditTimezone;

readonly class PostData
{
    /** @param string[] $rawPostData */
    public static function fromRawPostData(array $rawPostData): self
    {
        return new self(Timezone::fromNative(
            $rawPostData['value'] ?? '',
        ));
    }

    public function __construct(public Timezone $timezone)
    {
    }
}
