<?php declare(strict_types=1);

namespace ApiCommon\Entity;

use DateTimeInterface;

interface UpdatedAtInterface
{
    public function getUpdatedAt(): ?DateTimeInterface;

    public function setUpdatedAt(DateTimeInterface $updatedAt): self;
}