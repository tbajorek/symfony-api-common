<?php declare(strict_types=1);

namespace ApiCommon\Entity\Config;

use ApiCommon\Entity\EntityInterface;
use ApiCommon\Model\Config\BackendModel\DatabaseValue;
use ApiCommon\Model\Config\Metadata;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity]
#[Table(name: 'config_definitions')]
#[UniqueEntity('path')]
class Definition implements EntityInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $path = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $label = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $sortOrder = null;

    #[ORM\ManyToOne(inversedBy: 'definitions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ConfigGroup $configGroup = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $frontendModel = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $backendModel = DatabaseValue::class;

    #[ORM\Column(type: Types::JSON, nullable: false)]
    #[Assert\Length(max: 255)]
    private ?Metadata $metadata = null;

    #[ORM\OneToMany(mappedBy: 'definition', targetEntity: Value::class, orphanRemoval: true)]
    private Collection $values;

    public function __construct()
    {
        $this->values = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
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

    public function getFrontendModel(): ?string
    {
        return $this->frontendModel;
    }

    public function setFrontendModel(string $frontendModel): self
    {
        $this->frontendModel = $frontendModel;
        return $this;
    }

    public function getBackendModel(): ?string
    {
        return $this->backendModel;
    }

    public function setBackendModel(string $backendModel): self
    {
        $this->backendModel = $backendModel;
        return $this;
    }

    public function getMetadata(): ?Metadata
    {
        return $this->metadata;
    }

    public function setMetadata(Metadata $metadata): self
    {
        $this->metadata = $metadata;
        return $this;
    }

    public function getConfigGroup(): ?ConfigGroup
    {
        return $this->configGroup;
    }

    public function setConfigGroup(?ConfigGroup $configGroup): self
    {
        $this->configGroup = $configGroup;
        return $this;
    }

    /**
     * @return Collection<int, Value>
     */
    public function getValues(): Collection
    {
        return $this->values;
    }

    public function addValue(Value $value): self
    {
        if (!$this->values->contains($value)) {
            $this->values->add($value);
            $value->setDefinition($this);
        }
        return $this;
    }

    public function removeValue(Value $value): self
    {
        // set the owning side to null (unless already changed)
        if ($this->values->removeElement($value) && $value->getDefinition() === $this) {
            $value->setDefinition(null);
        }
        return $this;
    }
}
