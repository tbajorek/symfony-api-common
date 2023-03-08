<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Entity;

use ApiCommon\Entity\EntityInterface;
use ApiCommon\Model\Configuration;
use Doctrine\ORM\EntityManagerInterface;

interface EntityInstallerInterface
{
    public function setDependencies(
        EntityHydrator $hydrator,
        Configuration $configuration,
        EntityManagerInterface $entityManager
    ): void;

    public function hydrateEntity(array $data, ?string $entityName = null): HydratedValue;

    public function hydrate(array $data): HydratedValue;

    public function getEntityName(): string;

    public function getEntityClass(): string;

    public function persist(EntityInterface $entity): void;

    /**
     * @param EntityInterface[]|null $entities
     */
    public function flush(?array $entities = null): void;
}