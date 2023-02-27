<?php declare(strict_types=1);

namespace ApiCommon\Entity\User;

use ApiCommon\Entity\EntityInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity]
#[Table(name: 'users')]
#[UniqueEntity('email')]
class User implements EntityInterface, UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 64)]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email',
    )]
    #[Assert\NotBlank]
    #[Assert\Length(max: 64)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 64)]
    private ?string $name = null;

    #[ORM\Column(length: 128, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 128)]
    private ?string $surname = null;

    #[ORM\Column]
    private array $roles = [];

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->getId() ? $this->getId()->toRfc4122() : '';
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }
}
