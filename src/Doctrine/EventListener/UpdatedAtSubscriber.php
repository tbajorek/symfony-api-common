<?php declare(strict_types=1);

namespace ApiCommon\Doctrine\EventListener;

use ApiCommon\Entity\CreatedAtInterface;
use ApiCommon\Entity\UpdatedAtInterface;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

class UpdatedAtSubscriber implements EventSubscriberInterface
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
        ];
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();
        $now = new DateTime("now");
        if ($entity instanceof CreatedAtInterface) {
            $entity->setCreatedAt($now);
        }
        if ($entity instanceof UpdatedAtInterface) {
            $entity->setUpdatedAt($now);
        }
    }
}