<?php

declare(strict_types=1);

namespace MissionControlIdp\Dependencies;

use MissionControlBackend\ContainerBindings;
use MissionControlIdp\IdpRedisAdapter;
use MissionControlIdp\IdpStoragePool;
use Psr\Container\ContainerInterface;
use Redis;

use function assert;

class RegisterBindingsStorage
{
    public static function register(ContainerBindings $containerBindings): void
    {
        $containerBindings->addBinding(
            IdpStoragePool::class,
            IdpRedisAdapter::class,
        );

        $containerBindings->addBinding(
            IdpRedisAdapter::class,
            static function (
                ContainerInterface $container,
            ): IdpRedisAdapter {
                $redis = $container->get(Redis::class);

                assert($redis instanceof Redis);

                return new IdpRedisAdapter(
                    $redis,
                    'mission_control_idp',
                );
            },
        );
    }
}
