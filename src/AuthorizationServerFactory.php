<?php

declare(strict_types=1);

namespace MissionControlIdp;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

readonly class AuthorizationServerFactory
{
    public function __construct(
        private AuthorizationServerFactoryConfig $config,
        private ScopeRepositoryInterface $scopeRepository,
        private ClientRepositoryInterface $clientRepository,
        private AuthCodeRepositoryInterface $authCodeRepository,
        private AccessTokenRepositoryInterface $accessTokenRepository,
        private RefreshTokenRepositoryInterface $refreshTokenRepository,
    ) {
    }

    public function create(): AuthorizationServer
    {
        $server = new AuthorizationServer(
            clientRepository: $this->clientRepository,
            accessTokenRepository: $this->accessTokenRepository,
            scopeRepository: $this->scopeRepository,
            privateKey: $this->config->privateKeyPath,
            encryptionKey: $this->config->encryptionKey,
        );

        $this->setUpAuthCodeGrant(server: $server);

        $this->setUpRefreshTokenGrant(server: $server);

        return $server;
    }

    private function setUpAuthCodeGrant(AuthorizationServer $server): void
    {
        $grant = new AuthCodeGrant(
            authCodeRepository: $this->authCodeRepository,
            refreshTokenRepository: $this->refreshTokenRepository,
            authCodeTTL: $this->config->authCodeExpiration,
        );

        $grant->setRefreshTokenTTL(
            refreshTokenTTL: $this->config->refreshTokenExpiration,
        );

        // Enable the authentication code grant on the server
        $server->enableGrantType(
            grantType: $grant,
            accessTokenTTL: $this->config->accessTokenExpiration,
        );
    }

    private function setUpRefreshTokenGrant(AuthorizationServer $server): void
    {
        $grant = new RefreshTokenGrant(
            refreshTokenRepository: $this->refreshTokenRepository,
        );

        $grant->setRefreshTokenTTL(
            refreshTokenTTL: $this->config->refreshTokenExpiration,
        );

        // Enable the authentication code grant on the server
        $server->enableGrantType(
            grantType: $grant,
            accessTokenTTL: $this->config->accessTokenExpiration,
        );
    }
}
