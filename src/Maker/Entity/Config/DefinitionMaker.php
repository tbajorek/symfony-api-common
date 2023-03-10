<?php declare(strict_types=1);

namespace ApiCommon\Maker\Entity\Config;

use ApiCommon\Entity\Config\DefinitionInterface;
use ApiCommon\Entity\Config\GroupInterface;
use ApiCommon\Entity\Config\ValueInterface;
use ApiCommon\Maker\Entity\AbstractEntityMaker;
use ApiCommon\Model\Config\Metadata;
use ApiCommon\Model\Maker\Entity\EntityField;
use Doctrine\DBAL\Types\Types;
use Generator;
use ApiCommon\Model\Maker\Entity\EntityRelation;

class DefinitionMaker extends AbstractEntityMaker
{
    public static function getCommandDescription(): string
    {
        return 'Create configuration definition entity';
    }

    public function getInterfaces(): array
    {
        return [
            DefinitionInterface::class
        ];
    }

    public function getFields(): Generator
    {
        yield new EntityField('path', Types::STRING, false, ['length' => 255, 'unique' => true]);
        yield new EntityField('label', Types::STRING, false, ['length' => 255]);
        yield new EntityField('description', Types::STRING, true, ['length' => 255]);
        yield new EntityField('sortOrder', Types::INTEGER);

        $groupRelation = new EntityRelation(
            EntityRelation::MANY_TO_ONE,
            $this->classNameResolver->resolve(self::getEntityClassName()),
            $this->classNameResolver->resolve(ConfigGroupMaker::getEntityClassName())
        );
        $groupRelation->setOwningProperty('configGroup');
        $groupRelation->setInverseProperty('definitions');
        $groupRelation->setIsNullable(false);
        $groupRelation->setOrphanRemoval(true);
        $groupRelation->setCustomReturnType(GroupInterface::class);
        $groupRelation->setCustomInverseReturnType(DefinitionInterface::class);
        yield $groupRelation;

        yield new EntityField('frontendModel', Types::STRING, false, ['length' => 255]);
        yield new EntityField('backendModel', Types::STRING, false, ['length' => 255]);
        yield new EntityField('metadata', Types::JSON, false, [], Metadata::class);
        yield new EntityField('frontendVisibility', Types::BOOLEAN);
    }

    public static function getEntityClassName(): string
    {
        return 'Config\\Definition';
    }

    public static function getTableName(): ?string
    {
        return 'config_definitions';
    }

    public function getDependencies(): array
    {
        return [
            ScopeMaker::class,
            ValueMaker::class,
            ConfigGroupMaker::class
        ];
    }
}