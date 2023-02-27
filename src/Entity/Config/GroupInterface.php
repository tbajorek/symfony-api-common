<?php declare(strict_types=1);

namespace ApiCommon\Entity\Config;

use ApiCommon\Entity\EntityInterface;
use Doctrine\Common\Collections\Collection;

interface GroupInterface extends EntityInterface
{
    public function getLabel(): ?string;

    public function setLabel(string $label): self;

    public function getSortOrder(): ?int;

    public function setSortOrder(int $sortOrder): self;

    public function getDefinitions(): Collection;

    public function addDefinition(DefinitionInterface $definition): self;

    public function removeDefinition(DefinitionInterface $definition): self;
}
