<?php declare(strict_types=1);

namespace ApiCommon\Entity\Config;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'config_groups')]
class ConfigGroup implements GroupInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $label = null;

    #[ORM\Column]
    private ?int $sortOrder = null;

    #[ORM\OneToMany(mappedBy: 'configGroup', targetEntity: Definition::class, orphanRemoval: true)]
    private Collection $definitions;

    public function __construct()
    {
        $this->definitions = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): self
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }

    /**
     * @return Collection<int, DefinitionInterface>
     */
    public function getDefinitions(): Collection
    {
        return $this->definitions;
    }

    public function addDefinition(DefinitionInterface $definition): self
    {
        if (!$this->definitions->contains($definition)) {
            $this->definitions->add($definition);
            $definition->setConfigGroup($this);
        }
        return $this;
    }

    public function removeDefinition(DefinitionInterface $definition): self
    {
        // set the owning side to null (unless already changed)
        if ($this->definitions->removeElement($definition) && $definition->getConfigGroup() === $this) {
            $definition->setConfigGroup(null);
        }
        return $this;
    }
}
