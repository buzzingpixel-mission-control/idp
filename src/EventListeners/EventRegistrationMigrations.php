<?php

declare(strict_types=1);

namespace MissionControlIdp\EventListeners;

use MissionControlBackend\Persistence\Migrations\AddMigrationPathsEvent;
use MissionControlIdp\IdpSrc;

class EventRegistrationMigrations
{
    public function onAddMigrationPaths(AddMigrationPathsEvent $event): void
    {
        $event->paths->addPathFromString(IdpSrc::path() . '/Migrations');
    }
}
