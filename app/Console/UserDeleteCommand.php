<?php declare(strict_types = 1);

namespace App\Console;

use App\Domain\User\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
	name: 'user:delete',
	description: 'Delete a user by ID or email',
)]
final class UserDeleteCommand extends Command
{

	public function __construct(
		private readonly UserRepository $userRepository,
	)
	{
		parent::__construct();
	}

	protected function configure(): void
	{
		$this
			->addArgument('identifier', InputArgument::REQUIRED, 'User ID or email address')
			->addOption('force', 'f', InputOption::VALUE_NONE, 'Skip confirmation prompt');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);

		$identifier = $input->getArgument('identifier');
		$force = $input->getOption('force') === true;

		if (!is_string($identifier) || $identifier === '') {
			$io->error('Identifier must be a non-empty string.');

			return Command::FAILURE;
		}

		// Try to find by ID or email
		$user = is_numeric($identifier) ? $this->userRepository->find((int) $identifier) : $this->userRepository->findByEmail($identifier);

		if ($user === null) {
			$io->error(sprintf('User "%s" not found.', $identifier));

			return Command::FAILURE;
		}

		$io->note(sprintf('Found user: %s <%s> (ID: %d)', $user->getName(), $user->getEmail(), $user->getId()));

		if (!$force) {
			$confirm = $io->confirm('Are you sure you want to delete this user?', false);
			if (!$confirm) {
				$io->warning('Operation cancelled.');

				return Command::SUCCESS;
			}
		}

		$this->userRepository->delete($user);

		$io->success(sprintf('User "%s" <%s> has been deleted.', $user->getName(), $user->getEmail()));

		return Command::SUCCESS;
	}

}
