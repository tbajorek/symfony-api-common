<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Entity;

use ApiCommon\Entity\EntityInterface;

class HydratedValue
{
    public function __construct(private readonly string|int|null $id, private readonly EntityInterface $entity)
    {
    }

    public function getId(): string|int|null
    {
        return $this->id;
    }

    public function getEntity(): EntityInterface
    {
        return $this->entity;
    }
}