<?php declare(strict_types = 1);

namespace App\Console;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
	name: 'user:new',
	description: 'Create a new user',
)]
final class UserNewCommand extends Command
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
			->addOption('email', 'e', InputOption::VALUE_REQUIRED, 'User email address')
			->addOption('name', null, InputOption::VALUE_REQUIRED, 'User name');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);

		$email = $input->getOption('email');
		$name = $input->getOption('name');

		// Interactive mode if options not provided
		if (!is_string($email) || $email === '') {
			$email = $io->ask('Email address');
		}

		if (!is_string($name) || $name === '') {
			$name = $io->ask('Name');
		}

		if (!is_string($email) || $email === '') {
			$io->error('Email is required.');

			return Command::FAILURE;
		}

		if (!is_string($name) || $name === '') {
			$io->error('Name is required.');

			return Command::FAILURE;
		}

		// Validate email format
		if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
			$io->error('Invalid email format.');

			return Command::FAILURE;
		}

		// Check if user already exists
		$existingUser = $this->userRepository->findByEmail($email);
		if ($existingUser !== null) {
			$io->error(sprintf('User with email "%s" already exists.', $email));

			return Command::FAILURE;
		}

		$user = new User($email, $name);
		$this->userRepository->save($user);

		$io->success(sprintf('User "%s" <%s> created with ID %d.', $name, $email, $user->getId()));

		return Command::SUCCESS;
	}

}
