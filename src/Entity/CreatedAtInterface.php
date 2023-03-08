<?php declare(strict_types=1);

namespace ApiCommon\Entity;

use DateTimeInterface;

interface CreatedAtInterface
{
    public function getCreatedAt(): ?DateTimeInterface;

    public function setCreatedAt(DateTimeInterface $createdAt): self;
}