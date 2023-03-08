<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Repository;

use ApiCommon\Entity\EntityInterface;
use ApiCommon\Exception\Installer\SharedValueNotSetException;
use ApiCommon\Model\Installer\Entity\EntityInstallerInterface;

interface SharingEntitiesInstallerInterface extends EntityInstallerInterface
{
    public function getSharingMode(): bool;

    public function setSharedDataRepository(SharedDataRepository $repository): void;

    public function shareEntity(string|int $id, EntityInterface $entity, ?string $entityName = null): void;

    public function getSharedEntity(string $entityName, string $id): EntityInterface;
}