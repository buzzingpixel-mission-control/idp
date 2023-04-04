<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\ValueObjects;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Funeralzone\ValueObjects\ValueObject;
use InvalidArgumentException;
use MissionControlBackend\Persistence\DateFormats;

use function implode;
use function is_string;

class CreatedAt implements ValueObject
{
    public function __construct(protected DateTimeImmutable $date)
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
        $format = DateFormats::POSTGRES_ISO8601;

        $invalid = new InvalidArgumentException(
            implode(' ', [
                'String must be a valid date in',
                $format,
                'format.',
            ]),
        );

        if (! is_string($native)) {
            throw $invalid;
        }

        $date = DateTimeImmutable::createFromFormat(
            $format,
            $native,
            new DateTimeZone('UTC'),
        );

        if ($date === false) {
            throw throw $invalid;
        }

        return new self($date);
    }

    public function toNative(): string
    {
        return $this->date->format(DateTimeInterface::ATOM);
    }
}
