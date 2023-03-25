<?php

declare(strict_types=1);

namespace MissionControlIdp\EventListeners;

use Crell\Tukio\OrderedProviderInterface;

class EventRegistration
{
    public static function register(OrderedProviderInterface $provider): void
    {
        $provider->addSubscriber(
            AuthRouting::class,
            AuthRouting::class,
        );

        $provider->addSubscriber(
            EventRegistrationMigrations::class,
            EventRegistrationMigrations::class,
        );
    }
}
