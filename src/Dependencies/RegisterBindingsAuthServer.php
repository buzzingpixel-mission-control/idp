<?php

declare(strict_types=1);

namespace MissionControlIdp\Dependencies;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use MissionControlBackend\ContainerBindings;
use MissionControlIdp\AccessToken\AccessTokenRepository;
use MissionControlIdp\AuthCode\AuthCodeRepository;
use MissionControlIdp\AuthorizationServerFactory;
use MissionControlIdp\Client\ClientRepository;
use MissionControlIdp\RefreshToken\RefreshTokenRepository;
use MissionControlIdp\Scope\ScopeRepository;
use Psr\Container\ContainerInterface;

class RegisterBindingsAuthServer
{
    public static function register(ContainerBindings $containerBindings): void
    {
        $containerBindings->addBinding(
            key: ScopeRepositoryInterface::class,
            value: ScopeRepository::class,
        );

        $containerBindings->addBinding(
            key: ClientRepositoryInterface::class,
            value: ClientRepository::class,
        );

        $containerBindings->addBinding(
            key: AuthCodeRepositoryInterface::class,
            value: AuthCodeRepository::class,
        );

        $containerBindings->addBinding(
            key: AccessTokenRepositoryInterface::class,
            value: AccessTokenRepository::class,
        );

        $containerBindings->addBinding(
            key: RefreshTokenRepositoryInterface::class,
            value: RefreshTokenRepository::class,
        );

        $containerBindings->addBinding(
            AuthorizationServer::class,
            /** @phpstan-ignore-next-line */
            static fn (ContainerInterface $container) => $container->get(
                AuthorizationServerFactory::class,
            )->create(),
        );
    }
}
