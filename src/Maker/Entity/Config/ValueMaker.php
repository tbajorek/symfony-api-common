<?php declare(strict_types=1);

namespace ApiCommon\Maker\Entity\Config;

use ApiCommon\Entity\Config\DefinitionInterface;
use ApiCommon\Entity\Config\ScopeInterface;
use ApiCommon\Entity\Config\ValueInterface;
use ApiCommon\Maker\Entity\AbstractEntityMaker;
use ApiCommon\Model\Maker\Entity\EntityField;
use ApiCommon\Model\Maker\Entity\EntityRelation;
use Doctrine\DBAL\Types\Types;
use Generator;

class ValueMaker extends AbstractEntityMaker
{
    public static function getCommandDescription(): string
    {
        return 'Create configuration value entity';
    }

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
        $definitionRelation = new EntityRelation(
            EntityRelation::MANY_TO_ONE,
            $this->classNameResolver->resolve(self::getEntityClassName()),
            $this->classNameResolver->resolve(DefinitionMaker::getEntityClassName())
        );
        $definitionRelation->setOwningProperty('definition');
        $definitionRelation->setInverseProperty('values');
        $definitionRelation->setIsNullable(false);
        $definitionRelation->setOrphanRemoval(true);
        $definitionRelation->setCustomReturnType(DefinitionInterface::class);
        $definitionRelation->setCustomInverseReturnType(ValueInterface::class);
        yield $definitionRelation;

        $scopeRelation = new EntityRelation(
            EntityRelation::MANY_TO_ONE,
            $this->classNameResolver->resolve(self::getEntityClassName()),
            $this->classNameResolver->resolve(ScopeMaker::getEntityClassName())
        );
        $scopeRelation->setOwningProperty('scope');
        $scopeRelation->setColumnName('scope');
        $scopeRelation->setMapInverseRelation(false);
        $scopeRelation->setIsNullable(false);
        $scopeRelation->setCustomReturnType(ScopeInterface::class);
        yield $scopeRelation;

        yield new EntityField('scopeId', 'uuid', true, ['unique' => false]);
        yield new EntityField('value', Types::STRING, false, ['length' => 255]);
        yield new EntityField('updatedAt', Types::DATETIME_MUTABLE, false, ['length' => 255]);
    }

    public function getDependencies(): array
    {
        return [
            ScopeMaker::class,
            ConfigGroupMaker::class,
            DefinitionMaker::class,
        ];
    }

    public static function getEntityClassName(): string
    {
        return 'Config\\Value';
    }

    public static function getTableName(): ?string
    {
        return 'config_values';
    }

    public static function getUniqueConstraintFields(): array
    {
        return [
            'definition_id',
            'scope',
            'scope_id',
        ];
    }
}