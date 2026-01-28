<?php declare(strict_types = 1);

namespace App\Console;

use App\Domain\User\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
	name: 'user:list',
	description: 'List all users',
)]
final class UserListCommand extends Command
{

	public function __construct(
		private readonly UserRepository $userRepository,
	)
	{
		parent::__construct();
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$users = $this->userRepository->findAllOrderedByName();

		if (count($users) === 0) {
			$output->writeln('<info>No users found.</info>');

			return Command::SUCCESS;
		}

		$table = new Table($output);
		$table->setHeaders(['ID', 'Email', 'Name', 'Created At']);

		foreach ($users as $user) {
			$table->addRow([
				$user->getId(),
				$user->getEmail(),
				$user->getName(),
				$user->getCreatedAt()->format('Y-m-d H:i:s'),
			]);
		}

		$table->render();

		$output->writeln(sprintf('<info>Total: %d user(s)</info>', count($users)));

		return Command::SUCCESS;
	}

}
