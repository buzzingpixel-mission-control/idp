<?php

declare(strict_types=1);

namespace MissionControlIdp\Scope;

use League\OAuth2\Server\Entities\ScopeEntityInterface;

readonly class Scope implements ScopeEntityInterface
{
    public function __construct(private string $identifier)
    {
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function jsonSerialize(): string
    {
        return $this->getIdentifier();
    }
}
