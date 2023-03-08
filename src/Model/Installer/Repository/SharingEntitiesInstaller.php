<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Repository;

use ApiCommon\Entity\EntityInterface;

trait SharingEntitiesInstaller
{
    private SharedDataRepository $repository;

    public function setSharedDataRepository(SharedDataRepository $repository): void
    {
        $this->repository = $repository;
    }

    public function shareEntity(string|int $id, EntityInterface $entity): void
    {
        $this->repository->add(
            $this->repository->getEntityIdentifier($this->getEntityName(), (string)$id),
            $entity
        );
    }

    public function getSharedEntity(string $entityName, string|int $id): EntityInterface
    {
        return $this->repository->get($this->repository->getEntityIdentifier($entityName, (string)$id));
    }

    public function getSharingMode(): bool
    {
        return true;
    }

    abstract public function getEntityName(): string;
}