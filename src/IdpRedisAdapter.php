<?php

declare(strict_types=1);

namespace MissionControlIdp;

use Symfony\Component\Cache\Adapter\RedisAdapter;

class IdpRedisAdapter extends RedisAdapter implements IdpStoragePool
{
}
