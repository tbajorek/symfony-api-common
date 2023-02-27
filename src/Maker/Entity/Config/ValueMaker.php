<?php declare(strict_types=1);

namespace ApiCommon\Maker\Entity\Config;

use ApiCommon\Entity\Config\ValueInterface;
use ApiCommon\Maker\Entity\AbstractEntityMaker;
use ApiCommon\Model\Maker\Entity\EntityField;
use Doctrine\DBAL\Types\Types;
use Generator;
use ApiCommon\Model\Maker\Entity\EntityRelation;
use ApiCommon\Entity\Config\DefinitionInterface;

class ValueMaker extends AbstractEntityMaker
{
    public function getInterfaces(): array
    {
        return [
            ValueInterface::class
        ];
    }

    /**
     * {@inheritDoc}
     * @return Generator<EntityRelation|EntityField>
     */
    public function getFields(): Generator
    {
        $definitionRelation = new \ApiCommon\Model\Maker\Entity\EntityRelation(
            EntityRelation::MANY_TO_ONE,
            self::getEntityClass(),
            DefinitionMaker::getEntityClass()
        );
        $definitionRelation->setOwningProperty('definition');
        $definitionRelation->setInverseProperty('values');
        $definitionRelation->setIsNullable(false);
        $definitionRelation->setOrphanRemoval(true);
        $definitionRelation->setCustomReturnType(DefinitionInterface::class);
        $definitionRelation->setCustomInverseReturnType(ValueInterface::class);
        yield $definitionRelation;

        yield new EntityField('scope', Types::STRING, false, ['length' => 255]);
        yield new EntityField('scopeId', 'uuid', true, ['unique' => false]);
        yield new EntityField('value', Types::STRING, false, ['length' => 255]);
        yield new EntityField('updatedAt', Types::DATETIME_MUTABLE, false, ['length' => 255]);
    }

    public function getDependencies(): array
    {
        return [
            DefinitionMaker::class,
            ConfigGroupMaker::class
        ];
    }

    public static function getEntityClass(): string
    {
        return 'App\Entity\Config\Value';
    }

    public static function getTableName(): ?string
    {
        return 'config_values';
    }
}