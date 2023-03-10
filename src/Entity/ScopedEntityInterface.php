<?php declare(strict_types=1);

namespace ApiCommon\Entity;

use DateTimeInterface;
use Symfony\Component\Uid\Uuid;

interface ScopedEntityInterface extends EntityInterface
{
    public function getParentScopeId(): ?Uuid;
}