<?php declare(strict_types=1);

namespace ApiCommon\Maker\Entity\Config;

use ApiCommon\Entity\Config\DefinitionInterface;
use ApiCommon\Maker\Entity\AbstractEntityMaker;
use ApiCommon\Model\Maker\Entity\EntityField;
use Doctrine\DBAL\Types\Types;
use Generator;
use Symfony\Bundle\MakerBundle\Doctrine\EntityRelation;

class DefinitionMaker extends AbstractEntityMaker
{
    public function getInterfaces(): array
    {
        return [
            DefinitionInterface::class
        ];
    }

    public function getFields(): Generator
    {
        yield new EntityField('path', Types::STRING, false, ['length' => 255]);
        yield new EntityField('label', Types::STRING, false, ['length' => 255]);
        yield new EntityField('sortOrder', Types::INTEGER);

        $groupRelation = new EntityRelation(
            EntityRelation::MANY_TO_ONE,
            self::getEntityClass(),
            ConfigGroupMaker::getEntityClass()
        );
        $groupRelation->setOwningProperty('configGroup');
        $groupRelation->setInverseProperty('definitions');
        $groupRelation->setIsNullable(false);
        $groupRelation->setOrphanRemoval(true);
        yield $groupRelation;

        yield new EntityField('frontendModel', Types::STRING, false, ['length' => 255]);
        yield new EntityField('backendModel', Types::STRING, false, ['length' => 255]);
        yield new EntityField('metadata', Types::JSON);
    }

    public static function getEntityClass(): string
    {
        return 'App\Entity\Config\Definition';
    }

    public static function getTableName(): ?string
    {
        return 'config_definitions';
    }

    public static function getUniqueConstraintFields(): array
    {
        return [
            'path'
        ];
    }

    public function getDependencies(): array
    {
        return [
            ValueMaker::class,
            ConfigGroupMaker::class
        ];
    }
}