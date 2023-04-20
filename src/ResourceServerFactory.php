<?php

declare(strict_types=1);

namespace MissionControlIdp;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\ResourceServer;

readonly class ResourceServerFactory
{
    public function __construct(
        private AuthorizationServerFactoryConfig $config,
        private AccessTokenRepositoryInterface $accessTokenRepository,
    ) {
    }

    public function create(): ResourceServer
    {
        return new ResourceServer(
            $this->accessTokenRepository,
            $this->config->publicKeyPath,
            null,
        );
    }
}
