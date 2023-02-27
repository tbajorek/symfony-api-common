<?php declare(strict_types=1);

namespace ApiCommon\Maker\Entity\Config;

use ApiCommon\Entity\Config\GroupInterface;
use ApiCommon\Maker\Entity\AbstractEntityMaker;
use ApiCommon\Model\Maker\Entity\EntityField;
use Doctrine\DBAL\Types\Types;
use Generator;

class ConfigGroupMaker extends AbstractEntityMaker
{
    public static function getEntityClass(): string
    {
        return 'App\Entity\Config\ConfigGroupCopy';
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
}