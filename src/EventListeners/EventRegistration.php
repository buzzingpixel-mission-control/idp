<?php

declare(strict_types=1);

namespace MissionControlIdp\EventListeners;

use Crell\Tukio\OrderedProviderInterface;
use MissionControlIdp\IdentityManagement\CreateIdentityCommand;

class EventRegistration
{
    public static function register(OrderedProviderInterface $provider): void
    {
        $provider->addSubscriber(
            ApiRouting::class,
            ApiRouting::class,
        );

        $provider->addSubscriber(
            AuthRouting::class,
            AuthRouting::class,
        );

        $provider->addSubscriber(
            CreateIdentityCommand::class,
            CreateIdentityCommand::class,
        );

        $provider->addSubscriber(
            EventRegistrationMigrations::class,
            EventRegistrationMigrations::class,
        );
    }
}
