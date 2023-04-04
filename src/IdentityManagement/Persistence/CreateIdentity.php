<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\Persistence;

use DateTimeInterface;
use MissionControlBackend\Persistence\MissionControlPdo;
use MissionControlBackend\Persistence\UuidFactoryWithOrderedTimeCodec;
use MissionControlIdp\IdentityManagement\ActionResult;
use MissionControlIdp\IdentityManagement\EmailAddress;
use MissionControlIdp\IdentityManagement\EmailAddressCollection;
use Psr\Clock\ClockInterface;

use function filter_var;
use function implode;

use const FILTER_VALIDATE_EMAIL;

// phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

readonly class CreateIdentity
{
    public function __construct(
        private ClockInterface $clock,
        private MissionControlPdo $pdo,
        private FindIdentities $findIdentities,
        private UuidFactoryWithOrderedTimeCodec $uuidFactory,
    ) {
    }

    public function create(IdentityRecord $record): ActionResult
    {
        $result = $this->validateEmailAddress(
            $record->email_address,
        );

        if (! $result->success) {
            return $result;
        }

        $record->id = $this->uuidFactory->uuid4()->toString();

        $record->created_at = $this->clock->now()->format(
            DateTimeInterface::ATOM,
        );

        $statement = $this->pdo->prepare(implode(' ', [
            'INSERT INTO',
            $record->tableName(),
            $record->columnsAsInsertIntoString(),
            'VALUES',
            $record->columnsAsValuePlaceholders(),
        ]));

        if (! $statement->execute($record->asParametersArray())) {
            return new ActionResult(
                false,
                $this->pdo->errorInfo(),
                $this->pdo->errorCode(),
            );
        }

        return new ActionResult();
    }

    private function validateEmailAddress(string $emailAddress): ActionResult
    {
        $validate = filter_var(
            $emailAddress,
            FILTER_VALIDATE_EMAIL,
        );

        if ($validate === null || $validate === false) {
            return new ActionResult(
                false,
                ['Email address must be valid'],
            );
        }

        $existingUser = $this->findIdentities->findOneOrNull(
            parameters: new FindIdentityParameters(
                emailAddresses: new EmailAddressCollection(addresses: [
                    new EmailAddress(emailAddress: $emailAddress),
                ]),
            ),
        );

        if ($existingUser !== null) {
            return new ActionResult(
                false,
                ['User with email ' . $emailAddress . ' exists'],
            );
        }

        return new ActionResult();
    }
}
