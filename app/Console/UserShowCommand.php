<?php declare(strict_types = 1);

namespace App\Console;

use App\Domain\User\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
	name: 'user:show',
	description: 'Show user details by ID or email',
)]
final class UserShowCommand extends Command
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
			->addArgument('identifier', InputArgument::REQUIRED, 'User ID or email address');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);

		$identifier = $input->getArgument('identifier');

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

		$io->title('User Details');

		$io->definitionList(
			['ID' => (string) $user->getId()],
			['Email' => $user->getEmail()],
			['Name' => $user->getName()],
			['Created At' => $user->getCreatedAt()->format('Y-m-d H:i:s')],
			['Updated At' => $user->getUpdatedAt()?->format('Y-m-d H:i:s') ?? 'Never'],
		);

		return Command::SUCCESS;
	}

}
