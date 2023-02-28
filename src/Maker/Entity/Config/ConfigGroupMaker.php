<?php declare(strict_types=1);

namespace ApiCommon\Maker\Entity\Config;

use ApiCommon\Entity\Config\GroupInterface;
use ApiCommon\Maker\Entity\AbstractEntityMaker;
use ApiCommon\Model\Maker\Entity\EntityRelation;
use Doctrine\DBAL\Types\Types;
use Generator;
use ApiCommon\Model\Maker\Entity\EntityField;

class ConfigGroupMaker extends AbstractEntityMaker
{
    public static function getEntityClassName(): string
    {
        return 'Config\\ConfigGroup';
    }

    public static function getTableName(): ?string
    {
        return 'config_groups';
    }

    public function getInterfaces(): array
    {
        return [
            GroupInterface::class
        ];
    }

    public function getFields(): Generator
    {
        yield new EntityField('label', Types::STRING, false, ['length' => 255]);
        yield new EntityField('sortOrder', Types::INTEGER);
    }

    public function getDependencies(): array
    {
        return [
            ValueMaker::class,
            DefinitionMaker::class
        ];
    }
}