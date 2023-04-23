<?php

declare(strict_types=1);

namespace MissionControlIdp\Api\UserAdmin\AddEditUser\ValueObjects;

use DateTimeZone;
use Funeralzone\ValueObjects\ValueObject;
use InvalidArgumentException;
use Throwable;

class Timezone implements ValueObject
{
    public function __construct(protected DateTimeZone $timeZone)
    {
    }

    public function isNull(): bool
    {
        return false;
    }

    public function isSame(ValueObject $object): bool
    {
        return $this->toNative() === $object->toNative();
    }

    /** @inheritDoc */
    public static function fromNative($native): self
    {
        /** @phpstan-ignore-next-line */
        return self::fromString($native);
    }

    public static function fromString(string $native): self
    {
        try {
            return new self(new DateTimeZone($native));
        } catch (Throwable) {
            throw new InvalidArgumentException(
                'String must be a valid Timezone',
            );
        }
    }

    public function toNative(): string
    {
        return $this->timeZone->getName();
    }
}
