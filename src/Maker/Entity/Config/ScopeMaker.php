<?php declare(strict_types=1);

namespace ApiCommon\Maker\Entity\Config;

use ApiCommon\Entity\Config\ScopeInterface;
use ApiCommon\Maker\Entity\AbstractEntityMaker;
use Generator;
use ApiCommon\Model\Maker\Entity\EntityField;
use ApiCommon\Model\Maker\Entity\EntityRelation;
use Doctrine\DBAL\Types\Types;

class ScopeMaker extends AbstractEntityMaker
{
    public static function getCommandDescription(): string
    {
        return 'Create configuration scope entity';
    }

    public static function getEntityClassName(): string
    {
        return 'Config\\Scope';
    }

    public static function getTableName(): ?string
    {
        return 'config_scopes';
    }

    public function getInterfaces(): array
    {
        return [
            ScopeInterface::class
        ];
    }

    public function getFields(): Generator
    {
        yield new EntityField('name', Types::STRING, false, ['length' => 255, 'unique' => true]);
        yield new EntityField('targetEntity', Types::STRING, true, ['length' => 255]);
        yield new EntityField('sortOrder', Types::INTEGER);
        yield new EntityField('editRoles', Types::SIMPLE_ARRAY);
    }

    public function getDependencies(): array
    {
        return [
            ValueMaker::class,
            ConfigGroupMaker::class,
            DefinitionMaker::class,
        ];
    }
}