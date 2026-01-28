<?php declare(strict_types = 1);

namespace App\Domain\User;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @extends EntityRepository<User>
 */
final class UserRepository extends EntityRepository
{

	public function __construct(EntityManagerInterface $em)
	{
		parent::__construct($em, $em->getClassMetadata(User::class));
	}

	public function findByEmail(string $email): ?User
	{
		return $this->findOneBy(['email' => $email]);
	}

	/**
	 * @return User[]
	 */
	public function findAllOrderedByName(): array
	{
		return $this->findBy([], ['name' => 'ASC']);
	}

	public function save(User $user): void
	{
		$this->getEntityManager()->persist($user);
		$this->getEntityManager()->flush();
	}

	public function delete(User $user): void
	{
		$this->getEntityManager()->remove($user);
		$this->getEntityManager()->flush();
	}

}
