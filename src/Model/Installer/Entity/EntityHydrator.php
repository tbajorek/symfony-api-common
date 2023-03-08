<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Entity;

use ApiCommon\Entity\EntityInterface;
use ApiCommon\Exception\Entity\EntityHydrationFailed;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;

class EntityHydrator
{
    private Serializer $serializer;

    public function __construct(array $normalizers)
    {
        $this->serializer = new Serializer($normalizers);
    }

    public function hydrate(array $data, string $entityClassName): HydratedValue
    {
        $id = null;
        if (isset($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
        }
        $entity = $this->serializer->denormalize($data, $entityClassName);
        if (!$entity instanceof EntityInterface) {
            throw new EntityHydrationFailed('Hydrated object is not an entity');
        }
        return new HydratedValue($id, $entity);
    }
}