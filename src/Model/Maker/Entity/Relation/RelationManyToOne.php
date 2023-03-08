<?php declare(strict_types=1);

namespace ApiCommon\Model\Maker\Entity\Relation;

use Symfony\Bundle\MakerBundle\Doctrine\BaseRelation;

class RelationManyToOne extends BaseRelation
{
    public function __construct(
        string $propertyName,
        string $targetClassName,
        ?string $targetPropertyName = null,
        bool $isSelfReferencing = false,
        bool $mapInverseRelation = true,
        bool $avoidSetter = false,
        bool $isCustomReturnTypeNullable = false,
        ?string $customReturnType = null,
        bool $isOwning = false,
        bool $orphanRemoval = false,
        bool $isNullable = false,
        private ?string $columnName = null
    ) {
        parent::__construct(
            $propertyName,
            $targetClassName,
            $targetPropertyName,
            $isSelfReferencing,
            $mapInverseRelation,
            $avoidSetter,
            $isCustomReturnTypeNullable,
            $customReturnType,
            $isOwning,
            $orphanRemoval,
            $isNullable,
            $columnName
        );
    }

    public function getColumnName(): ?string
    {
        return $this->columnName;
    }
}
