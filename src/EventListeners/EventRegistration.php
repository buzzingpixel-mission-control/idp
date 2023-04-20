<?php

declare(strict_types=1);

namespace MissionControlIdp\EventListeners;

use Crell\Tukio\OrderedProviderInterface;
use MissionControlIdp\IdentityManagement\CreateUserCommand;

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
            CreateUserCommand::class,
            CreateUserCommand::class,
        );

        $provider->addSubscriber(
            EventRegistrationMigrations::class,
            EventRegistrationMigrations::class,
        );
    }
}
