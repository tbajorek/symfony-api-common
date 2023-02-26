<?php declare(strict_types=1);

namespace ApiCommon\Entity;

use DateTime;

interface CreatedAtInterface
{
    public function getCreatedAt(): DateTime;
    
    public function setCreatedAt(DateTime $createdAt): self;
}