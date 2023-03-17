<?php

declare(strict_types=1);

namespace MissionControlIdp\Dependencies;

use MissionControlBackend\ContainerBindings;

class RegisterBindings
{
    public static function register(ContainerBindings $containerBindings): void
    {
        RegisterBindingsStorage::register(containerBindings:  $containerBindings);
    }
}
