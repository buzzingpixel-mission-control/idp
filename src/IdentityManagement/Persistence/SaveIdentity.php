<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\Persistence;

use MissionControlBackend\Persistence\MissionControlPdo;
use MissionControlBackend\Persistence\StringCollection;
use MissionControlIdp\IdentityManagement\ActionResult;
use MissionControlIdp\IdentityManagement\ValueObjects\EmailAddress;
use MissionControlIdp\IdentityManagement\ValueObjects\EmailAddressCollection;
use Ramsey\Uuid\Uuid;

use function count;
use function implode;

// phpcs:disable Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps

readonly class SaveIdentity
{
    public function __construct(
        private MissionControlPdo $pdo,
        private FindIdentities $findIdentities,
    ) {
    }

    public function save(IdentityRecord $record): ActionResult
    {
        $validationResult = $this->validate($record);

        if (! $validationResult->success) {
            return $validationResult;
        }

        $statement = $this->pdo->prepare(implode(' ', [
            'UPDATE',
            $record->tableName(),
            'SET',
            $record->columnsAsUpdateSetPlaceholders(),
            'WHERE id = :id',
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

    private function validate(IdentityRecord $record): ActionResult
    {
        $messages = [];

        if (! Uuid::isValid($record->id)) {
            $messages[] = 'User ID must be a valid UUID';
        }

        $emailValidation = EmailAddress::nativeIsValid(
            $record->email_address,
        );

        if (! $emailValidation->success) {
            $messages[] = $emailValidation->messageAsString();
        }

        $existingIdentity = $this->findIdentities->findOneOrNull(
            new FindIdentityParameters(
                emailAddresses: new EmailAddressCollection(
                    [
                        EmailAddress::fromNative(
                            $record->email_address,
                        ),
                    ],
                ),
                notIds: new StringCollection([
                    $record->id,
                ]),
            ),
        );

        if ($existingIdentity !== null) {
            $messages[] = implode(' ', [
                'Identity with email',
                $record->email_address,
                'exists',
            ]);
        }

        return new ActionResult(
            count($messages) < 1,
            $messages,
        );
    }
}
