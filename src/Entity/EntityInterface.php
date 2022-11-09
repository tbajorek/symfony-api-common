<?php declare(strict_types=1);

namespace ApiCommon\Entity;

use Symfony\Component\Uid\Uuid;

interface EntityInterface
{
    public function getId(): ?Uuid;
}