<?php

declare(strict_types=1);

namespace MissionControlIdp\Client;

use League\OAuth2\Server\Entities\ClientEntityInterface;

readonly class Client implements ClientEntityInterface
{
    /** @param string[] $redirectUri */
    public function __construct(
        public string $identifier,
        public string $name,
        public array $redirectUri,
        public bool $isConfidential,
        public string $clientSecret = '',
    ) {
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     *
     * @inheritDoc
     */
    public function getRedirectUri(): array
    {
        return $this->redirectUri;
    }

    public function isConfidential(): bool
    {
        return $this->isConfidential;
    }
}
