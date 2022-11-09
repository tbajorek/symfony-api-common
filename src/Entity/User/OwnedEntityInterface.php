<?php declare(strict_types=1);

namespace ApiCommon\Entity\User;

use ApiCommon\Entity\EntityInterface;

interface OwnedEntityInterface extends EntityInterface
{
    public function setOwner(User $owner): self;

    public function getOwner(): User;
}