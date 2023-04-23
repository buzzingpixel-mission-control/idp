<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement;

use DateTimeZone;
use MissionControlIdp\IdentityManagement\ValueObjects\EmailAddress;
use MissionControlIdp\IdentityManagement\ValueObjects\IsAdmin;
use MissionControlIdp\IdentityManagement\ValueObjects\Name;
use MissionControlIdp\IdentityManagement\ValueObjects\Password;
use MissionControlIdp\IdentityManagement\ValueObjects\Timezone;
use Spatie\Cloneable\Cloneable;

readonly class NewIdentity
{
    use Cloneable;

    public function __construct(
        public EmailAddress $emailAddress,
        public IsAdmin $isAdmin = new IsAdmin(false),
        public Name $name = new Name(''),
        public Password $password = new Password(''),
        public Timezone $timezone = new Timezone(new DateTimeZone('US/Central')),
    ) {
    }
}
