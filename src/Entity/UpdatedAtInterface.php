<?php declare(strict_types=1);

namespace ApiCommon\Entity;

use DateTime;

interface UpdatedAtInterface
{
    public function getUpdatedAt(): ?DateTime;

    public function setUpdatedAt(DateTime $updatedAt): self;
}