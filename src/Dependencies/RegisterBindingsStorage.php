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
            key: IdpStoragePool::class,
            value: static function (
                ContainerInterface $container,
            ): IdpRedisAdapter {
                $redis = $container->get(Redis::class);

                assert($redis instanceof Redis);

                return new IdpRedisAdapter(
                    redis: $redis,
                    namespace: 'mission_control_idp',
                );
            },
        );
    }
}
