<?php

declare(strict_types=1);

namespace MissionControlIdp\Client;

use League\OAuth2\Server\Entities\ClientEntityInterface;

use function array_filter;
use function array_map;
use function array_values;
use function count;

readonly class ClientCollection
{
    /** @var Client[] */
    public array $clients;

    /** @param Client[] $clients */
    public function __construct(array $clients = [])
    {
        $this->clients = array_values(array_map(
            static fn (Client $c) => $c,
            $clients,
        ));
    }

    public function count(): int
    {
        return count($this->clients);
    }

    public function first(): Client
    {
        return array_values($this->clients)[0];
    }

    public function firstOrNull(): Client|null
    {
        return array_values($this->clients)[0] ?? null;
    }

    public function filter(callable $callback): ClientCollection
    {
        return new ClientCollection(
            array_filter($this->clients, $callback),
        );
    }

    public function getByIdentifier(string $identifier): ClientEntityInterface
    {
        return $this->filter(
            callback: static fn (
                Client $c,
            ) => $c->getIdentifier() === $identifier
        )->first();
    }

    public function getByIdentifierOrNull(string $identifier): Client|null
    {
        return $this->filter(
            callback: static fn (
                Client $c,
            ) => $c->getIdentifier() === $identifier
        )->firstOrNull();
    }
}
