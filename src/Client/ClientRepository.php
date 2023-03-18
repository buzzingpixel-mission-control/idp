<?php

declare(strict_types=1);

namespace MissionControlIdp\Client;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

readonly class ClientRepository implements ClientRepositoryInterface
{
    public function __construct(private ClientsConfig $config)
    {
    }

    /** @inheritDoc */
    public function getClientEntity(
        $clientIdentifier,
    ): ClientEntityInterface|null {
        return $this->config->clients->getByIdentifierOrNull(
            identifier: $clientIdentifier,
        );
    }

    /** @inheritDoc */
    public function validateClient(
        $clientIdentifier,
        $clientSecret,
        $grantType,
    ): bool {
        $client = $this->config->clients->getByIdentifierOrNull(
            identifier: $clientIdentifier,
        );

        if ($client === null) {
            return false;
        }

        if (
            $client->isConfidential() &&
            $clientSecret !== $client->clientSecret
        ) {
            return false;
        }

        // In the future, if we have other grant types, we'll want to expand
        // upon this
        return $grantType === 'code' ||
            $grantType === 'authorization_code' ||
            $grantType === 'refresh_token';
    }
}
