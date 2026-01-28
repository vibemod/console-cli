<?php declare(strict_types = 1);

namespace App\Domain\User;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{

	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column(type: 'integer')]
	private int $id;

	#[ORM\Column(type: 'string', unique: true)]
	private string $email;

	#[ORM\Column(type: 'string')]
	private string $name;

	#[ORM\Column(type: 'datetime')]
	private DateTime $createdAt;

	#[ORM\Column(type: 'datetime', nullable: true)]
	private ?DateTime $updatedAt = null;

	public function __construct(string $email, string $name)
	{
		$this->email = $email;
		$this->name = $name;
		$this->createdAt = new DateTime();
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail(string $email): void
	{
		$this->email = $email;
		$this->updatedAt = new DateTime();
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
		$this->updatedAt = new DateTime();
	}

	public function getCreatedAt(): DateTime
	{
		return $this->createdAt;
	}

	public function getUpdatedAt(): ?DateTime
	{
		return $this->updatedAt;
	}

}
