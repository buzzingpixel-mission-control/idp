<?php

declare(strict_types=1);

namespace MissionControlIdp\Dependencies;

use League\OAuth2\Server\ResourceServer;
use MissionControlBackend\ContainerBindings;
use MissionControlIdp\ResourceServerFactory;
use Psr\Container\ContainerInterface;

class RegisterBindingsResourceServer
{
    public static function register(ContainerBindings $containerBindings): void
    {
        $containerBindings->addBinding(
            ResourceServer::class,
            /** @phpstan-ignore-next-line */
            static fn (ContainerInterface $container) => $container->get(
                ResourceServerFactory::class,
            )->create(),
        );
    }
}
