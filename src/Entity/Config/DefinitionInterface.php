<?php declare(strict_types=1);

namespace ApiCommon\Entity\Config;

use ApiCommon\Entity\EntityInterface;
use ApiCommon\Model\Config\Metadata;
use Doctrine\Common\Collections\Collection;

interface DefinitionInterface extends EntityInterface
{
    public function getPath(): ?string;

    public function setPath(string $path): self;

    public function getLabel(): ?string;

    public function setLabel(string $label): self;

    public function getSortOrder(): ?int;

    public function setSortOrder(int $sortOrder): self;

    public function getFrontendModel(): ?string;

    public function setFrontendModel(string $frontendModel): self;

    public function getBackendModel(): ?string;

    public function setBackendModel(string $backendModel): self;

    public function getMetadata(): ?Metadata;

    public function setMetadata(Metadata $metadata): self;

    public function getConfigGroup(): ?GroupInterface;

    public function setConfigGroup(?GroupInterface $configGroup): self;

    public function getValues(): Collection;

    public function addValue(ValueInterface $value): self;

    public function removeValue(ValueInterface $value): self;
}
