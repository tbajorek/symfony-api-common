<?php declare(strict_types=1);

namespace ApiCommon\Entity\Config;

use ApiCommon\Entity\EntityInterface;
use ApiCommon\Entity\UpdatedAtInterface;
use Symfony\Component\Uid\Uuid;

interface ValueInterface extends EntityInterface, UpdatedAtInterface
{
    public function getDefinition(): ?Definition;

    public function setDefinition(?Definition $definition): self;

    public function getScope(): ?string;

    public function setScope(string $scope): self;

    public function getScopeId(): ?Uuid;

    public function setScopeId(?Uuid $scopeId): self;

    public function getValue(): ?string;

    public function setValue(string $value): self;
}
