<?php declare(strict_types=1);

namespace ApiCommon\Maker\Entity\Config;

use ApiCommon\Maker\Entity\AbstractEntityMaker;
use ApiCommon\Model\Maker\EntityField;
use Doctrine\DBAL\Types\Types;
use Generator;
use Symfony\Bundle\MakerBundle\Doctrine\EntityRelation;

/**
 * @method string getCommandDescription()
 */
class DefinitionMaker extends AbstractEntityMaker
{
    public static function getEntityClass(): string
    {
        return 'App\Entity\Config\DefinitionCopy';
    }

    public function getFields(): Generator
    {
        yield new EntityField('path', Types::STRING, false, ['length' => 255]);
        yield new EntityField('label', Types::STRING, false, ['length' => 255]);
        yield new EntityField('sortOrder', Types::INTEGER);

        $definitionRelation = new EntityRelation(
            EntityRelation::MANY_TO_ONE,
            self::getEntityClass(),
            DefinitionMaker::getEntityClass()
        );
        $definitionRelation->setOwningProperty('group');
        $definitionRelation->setInverseProperty('definitions');
        $definitionRelation->setIsNullable(false);
        $definitionRelation->setOrphanRemoval(true);
        yield $definitionRelation;

        yield new EntityField('frontendModel', Types::STRING, false, ['length' => 255]);
        yield new EntityField('backendModel', Types::STRING, false, ['length' => 255]);
        yield new EntityField('backendModel', Types::JSON);
    }
}