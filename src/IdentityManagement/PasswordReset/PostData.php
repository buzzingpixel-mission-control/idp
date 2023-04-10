<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement\PasswordReset;

use MissionControlIdp\IdentityManagement\ValueObjects\EmailAddress;
use Throwable;

use function count;

class PostData
{
    public bool $hasErrors;

    /** @var string[] */
    public array $errors;

    /** @param scalar[] $data */
    public static function fromArray(array $data): self
    {
        return new self((string) ($data['email'] ?? ''));
    }

    public function __construct(public string $email)
    {
        $errors = [];

        try {
            new EmailAddress($email);
        } catch (Throwable $e) {
            $errors[] = $e->getMessage();
        }

        $this->hasErrors = count($errors) > 0;

        $this->errors = $errors;
    }
}
