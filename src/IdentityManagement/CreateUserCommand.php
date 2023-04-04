<?php

declare(strict_types=1);

namespace MissionControlIdp\IdentityManagement;

use MissionControlBackend\Cli\ApplyCliCommandsEvent;
use MissionControlBackend\Cli\Question;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

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
                $emailAddress = new EmailAddress(
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

        $name = $this->question->ask('<fg=cyan>Name: </>');

        $password = $this->question->ask(
            '<fg=cyan>Password (leave blank to require reset): </>',
        );

        $isAdmin = $this->question->ask(
            '<fg=cyan>Is Admin (y/n): </>',
        );

        $result = $this->repository->createIdentity(new NewIdentity(
            $emailAddress,
            mb_strtolower($isAdmin) === 'y',
            $name,
            $password,
        ));

        return $result->success ? 0 : 1;
    }
}
