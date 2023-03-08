<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Entity;

use ApiCommon\Entity\EntityInterface;
use ApiCommon\Exception\Installer\WrongEntityException;
use ApiCommon\Model\Configuration;
use ApiCommon\Model\Installer\Entity\EntityHydrator;
use ApiCommon\Model\Installer\Entity\HydratedValue;
use Doctrine\ORM\EntityManagerInterface;

trait EntityInstaller
{
    private EntityHydrator $hydrator;
    private Configuration $configuration;
    private EntityManagerInterface $entityManager;

    public function setDependencies(
        EntityHydrator $hydrator,
        Configuration $configuration,
        EntityManagerInterface $entityManager
    ): void {
        $this->hydrator = $hydrator;
        $this->configuration = $configuration;
        $this->entityManager = $entityManager;
    }

    public function getEntityClass(): string
    {
        return $this->getEntityClassWithName();
    }

    public function hydrateEntity(array $data, ?string $entityName = null): HydratedValue
    {
        return $this->hydrator->hydrate($data, $this->getEntityClassWithName($entityName));
    }

    public function hydrate(array $data): HydratedValue
    {
        return $this->hydrateEntity($data);
    }

    public function persist(EntityInterface $entity): void
    {
        $this->entityManager->persist($entity);
    }
    
    public function flush(?array $entities = null): void
    {
        if ($entities !== null) {
            foreach ($entities as $entity) {
                if (!$entity instanceof EntityInterface) {
                    throw new WrongEntityException(sprintf('Class %s is not a correct entity'), $entity::class);
                }
                $this->persist($entity);
            }
        }
        $this->entityManager->flush();
    }

    protected function getEntityClassWithName(?string $entityName = null): string
    {
        return $this->configuration->getAppPrefix() . '\\Entity\\' . ($entityName ?? $this->getEntityName());
    }
}