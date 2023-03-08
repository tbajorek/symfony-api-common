<?php declare(strict_types=1);

namespace ApiCommon\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait UpdatedAt
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, length: 255, nullable: false)]
    private ?DateTimeInterface $updatedAt = null;

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    #[ORM\PreUpdate]
    public function onPreUpdate()
    {
        $this->updated = new \DateTime("now");
    }
}