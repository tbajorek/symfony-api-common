<?php declare(strict_types=1);

namespace ApiCommon\Model\Installer\Repository;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorResolverInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class SharedEntityNormalizer extends ObjectNormalizer
{
    private SharedDataRepository $sharedDataRepository;

    public function __construct(
        ClassMetadataFactoryInterface $classMetadataFactory = null,
        NameConverterInterface $nameConverter = null,
        PropertyAccessorInterface $propertyAccessor = null,
        PropertyTypeExtractorInterface $propertyTypeExtractor = null,
        ClassDiscriminatorResolverInterface $classDiscriminatorResolver = null,
        callable $objectClassResolver = null,
        array $defaultContext = [],
        SharedDataRepository $sharedDataRepository = null
    ) {
        parent::__construct($classMetadataFactory, $nameConverter, $propertyAccessor, $propertyTypeExtractor,
            $classDiscriminatorResolver, $objectClassResolver, $defaultContext);
        $this->sharedDataRepository = $sharedDataRepository;
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        $parts = explode('->', str_replace('@', '', $data));
        $id = $parts[0];
        $field = $parts[1] ?? null;
        $object = $this->sharedDataRepository->get($id);
        if ($field !== null) {
            $getterMethod = 'get' . ucfirst($field);
            return $object->$getterMethod();
        }
        return $this->sharedDataRepository->get($id);
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null)
    {
        return is_string($data) && preg_match('/^\@[a-zA-Z0-9\_]+\:[a-zA-Z0-9\_]+(\-\>[a-zA-Z0-9\_]+)$/i', $data) !== false;
    }
}