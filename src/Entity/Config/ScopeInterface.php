<?php declare(strict_types=1);

namespace ApiCommon\Entity\Config;

use ApiCommon\Entity\EntityInterface;

interface ScopeInterface extends EntityInterface
{
    public function getName(): ?string;

    public function setName(string $name): self;

    public function getSortOrder(): ?int;

    public function setSortOrder(int $sortOrder): self;
}