<?php

declare(strict_types=1);

namespace MissionControlIdp\Scope;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

use function array_filter;
use function array_values;
use function in_array;

class ScopeRepository implements ScopeRepositoryInterface
{
    // Might need to expand this later
    public const SCOPES = [
        'mission_control_access',
        'refresh_token',
    ];

    /** @inheritDoc */
    public function getScopeEntityByIdentifier(
        $identifier,
    ): ScopeEntityInterface|null {
        if (
            ! in_array(
                $identifier,
                self::SCOPES,
                true,
            )
        ) {
            return null;
        }

        return new Scope($identifier);
    }

    /** @inheritDoc */
    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null,
    ): array {
        return array_values(array_filter(
            $scopes,
            static fn (ScopeEntityInterface $s) => in_array(
                $s->getIdentifier(),
                self::SCOPES,
                true,
            ),
        ));
    }
}
