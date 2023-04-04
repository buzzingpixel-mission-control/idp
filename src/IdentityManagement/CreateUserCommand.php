<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement;

use Laminas\Escaper\Exception\RuntimeException;
use MissionControlBackend\Cli\ApplyCliCommandsEvent;
use MissionControlBackend\Cli\Question;
use MissionControlIdp\IdentityManagement\ValueObjects\EmailAddress;
use MissionControlIdp\IdentityManagement\ValueObjects\IsAdmin;
use MissionControlIdp\IdentityManagement\ValueObjects\Name;
use MissionControlIdp\IdentityManagement\ValueObjects\Password;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

use function implode;
use function mb_strtolower;

readonly class CreateUserCommand
{
    public static function onApplyCommands(ApplyCliCommandsEvent $event): void
    {
        $event->addCommand('identity:create', self::class);
    }

    public function __construct(
        private Question $question,
        private OutputInterface $output,
        private IdentityRepository $repository,
        private FormatterHelper $formatterHelper,
    ) {
    }

    public function __invoke(): int
    {
        $validEmail = false;

        do {
            try {
                $emailAddress = EmailAddress::fromNative(
                    $this->question->ask(
                        '<fg=cyan>Email Address (required): </>',
                        true,
                    ),
                );

                $validEmail = true;
            } catch (Throwable $exception) {
                $this->output->writeln(
                    $this->formatterHelper->formatBlock(
                        $exception->getMessage(),
                        'error',
                        true,
                    ),
                );
            }
        } while ($validEmail === false);

        $name = Name::fromNative(
            $this->question->ask('<fg=cyan>Name: </>'),
        );

        $password = Password::fromNative(
            $this->question->ask(
                '<fg=cyan>Password (leave blank to require reset): </>',
            ),
        );

        $isAdmin = IsAdmin::fromNative(
            mb_strtolower($this->question->ask(
                '<fg=cyan>Is Admin (y/n): </>',
            )) === 'y',
        );

        $result = $this->repository->createIdentity(new NewIdentity(
            $emailAddress,
            $isAdmin,
            $name,
            $password,
        ));

        if (! $result->success) {
            throw new RuntimeException(
                implode(', ', $result->message),
            );
        }

        return 0;
    }
}
