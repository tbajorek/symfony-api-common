<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Entity;

use ApiCommon\Entity\EntityInterface;
use ApiCommon\Exception\Installer\WrongEntityException;
use ApiCommon\Model\Configuration;
use ApiCommon\Model\Installer\Entity\EntityHydrator;
use ApiCommon\Model\Installer\Entity\HydratedValue;
use Doctrine\Persistence\ObjectManager;

trait EntityInstaller
{
    private EntityHydrator $hydrator;
    private Configuration $configuration;
    private ObjectManager $objectManager;

    public function setDependencies(
        EntityHydrator $hydrator,
        Configuration $configuration,
        ObjectManager $objectManager
    ): void {
        $this->hydrator = $hydrator;
        $this->configuration = $configuration;
        $this->objectManager = $objectManager;
    }

    public function getEntityClass(): string
    {
        return $this->configuration->getAppPrefix() . '\\Entity\\' . $this->getEntityName();
    }

    public function hydrate(array $data): HydratedValue
    {
        return $this->hydrator->hydrate($data, $this->getEntityClass());
    }

    public function persist(EntityInterface $entity): void
    {
        $this->objectManager->persist($entity);
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
        $this->objectManager->flush();
    }
}