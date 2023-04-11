<?php

declare(strict_types=1);

namespace MissionControlIdp\Security;

use OAuthProvider;

use function bin2hex;
use function ceil;
use function max;

class UrlSafeRandomTokenGenerator
{
    /** @param int $size Will be converted to a number divisible by 2 */
    public function generate(int $size = 40): string
    {
        $size = (int) ceil($size / 2);

        $size = max($size, 1);

        /**
         * We're not setting `strong` (2nd arg) to true on purpose. Based on
         * research, false (default) on `generateToken` will use /dev/urandom,
         * which will never block and is just fine. There's a small chance that
         * there will be less entropy if the number pool is small but it's
         * remote.
         */
        return bin2hex(OAuthProvider::generateToken($size));
    }
}
