<?php

declare(strict_types=1);

namespace MissionControlIdp\Client;

readonly class ClientsConfig
{
    public function __construct(public ClientCollection $clients)
    {
    }
}
