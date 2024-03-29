<?php declare(strict_types=1);

namespace ApiCommon\Model\Maker\Entity;

use Symfony\Bundle\MakerBundle\Doctrine\RelationManyToMany;
use Symfony\Bundle\MakerBundle\Doctrine\RelationOneToOne;
use ApiCommon\Model\Maker\Entity\Relation\RelationManyToOne;
use Symfony\Bundle\MakerBundle\Doctrine\RelationOneToMany;

/**
 * Custom definition of native class from Symfony to allow use more relation attributes than in the original one.
 */
class EntityRelation
{
    public const MANY_TO_ONE = 'ManyToOne';
    public const ONE_TO_MANY = 'OneToMany';
    public const MANY_TO_MANY = 'ManyToMany';
    public const ONE_TO_ONE = 'OneToOne';

    private $owningProperty;
    private $inverseProperty;
    private ?string $columnName = null;
    private bool $isNullable = false;
    private bool $isSelfReferencing = false;
    private bool $orphanRemoval = false;
    private bool $mapInverseRelation = true;
    private ?bool $isCustomReturnTypeNullable = true;
    private ?string $customReturnType = null;
    private ?string $customInverseReturnType = null;

    public function __construct(
        private string $type,
        private string $owningClass,
        private string $inverseClass,
    ) {
        if (!\in_array($type, self::getValidRelationTypes())) {
            throw new \Exception(sprintf('Invalid relation type "%s"', $type));
        }

        if (self::ONE_TO_MANY === $type) {
            throw new \Exception('Use ManyToOne instead of OneToMany');
        }

        $this->isSelfReferencing = $owningClass === $inverseClass;
    }

    public function setOwningProperty(string $owningProperty): void
    {
        $this->owningProperty = $owningProperty;
    }

    public function setColumnName(string $columnName): void
    {
        $this->columnName = $columnName;
    }

    public function setInverseProperty(string $inverseProperty): void
    {
        if (!$this->mapInverseRelation) {
            throw new \Exception('Cannot call setInverseProperty() when the inverse relation will not be mapped.');
        }

        $this->inverseProperty = $inverseProperty;
    }

    public function setIsNullable(bool $isNullable): void
    {
        $this->isNullable = $isNullable;
    }

    public function setIsCustomReturnTypeNullable(bool $isCustomReturnTypeNullable): void
    {
        $this->isCustomReturnTypeNullable = $isCustomReturnTypeNullable;
    }

    public function setOrphanRemoval(bool $orphanRemoval): void
    {
        $this->orphanRemoval = $orphanRemoval;
    }

    public function setCustomReturnType(string $customReturnType): void
    {
        $this->customReturnType = $customReturnType;
    }

    public function setCustomInverseReturnType(string $customInverseReturnType): void
    {
        $this->customInverseReturnType = $customInverseReturnType;
    }

    public static function getValidRelationTypes(): array
    {
        return [
            self::MANY_TO_ONE,
            self::ONE_TO_MANY,
            self::MANY_TO_MANY,
            self::ONE_TO_ONE,
        ];
    }

    public function getOwningRelation(): RelationManyToMany|RelationOneToOne|RelationManyToOne
    {
        return match ($this->getType()) {
            self::MANY_TO_ONE => (new RelationManyToOne(
                propertyName: $this->owningProperty,
                targetClassName: $this->inverseClass,
                targetPropertyName: $this->inverseProperty,
                isSelfReferencing: $this->isSelfReferencing,
                mapInverseRelation: $this->mapInverseRelation,
                customReturnType: $this->customReturnType,
                isCustomReturnTypeNullable: $this->isCustomReturnTypeNullable,
                isOwning: true,
                isNullable: $this->isNullable,
                columnName: $this->columnName,
            )),
            self::MANY_TO_MANY => (new RelationManyToMany(
                propertyName: $this->owningProperty,
                targetClassName: $this->inverseClass,
                targetPropertyName: $this->inverseProperty,
                isSelfReferencing: $this->isSelfReferencing,
                mapInverseRelation: $this->mapInverseRelation,
                customReturnType: $this->customReturnType,
                isCustomReturnTypeNullable: $this->isCustomReturnTypeNullable,
                isOwning: true,
            )),
            self::ONE_TO_ONE => (new RelationOneToOne(
                propertyName: $this->owningProperty,
                targetClassName: $this->inverseClass,
                targetPropertyName: $this->inverseProperty,
                isSelfReferencing: $this->isSelfReferencing,
                mapInverseRelation: $this->mapInverseRelation,
                customReturnType: $this->customReturnType,
                isOwning: true,
                isNullable: $this->isNullable,
            )),
            default => throw new \InvalidArgumentException('Invalid type'),
        };
    }

    public function getInverseRelation(): RelationManyToMany|RelationOneToOne|RelationOneToMany
    {
        return match ($this->getType()) {
            self::MANY_TO_ONE => (new RelationOneToMany(
                propertyName: $this->inverseProperty,
                targetClassName: $this->owningClass,
                targetPropertyName: $this->owningProperty,
                isSelfReferencing: $this->isSelfReferencing,
                orphanRemoval: $this->orphanRemoval,
                customReturnType: $this->customInverseReturnType,
                isCustomReturnTypeNullable: $this->isCustomReturnTypeNullable
            )),
            self::MANY_TO_MANY => (new RelationManyToMany(
                propertyName: $this->inverseProperty,
                targetClassName: $this->owningClass,
                targetPropertyName: $this->owningProperty,
                isSelfReferencing: $this->isSelfReferencing,
                customReturnType: $this->customInverseReturnType,
                isCustomReturnTypeNullable: $this->isCustomReturnTypeNullable,
            )),
            self::ONE_TO_ONE => (new RelationOneToOne(
                propertyName: $this->inverseProperty,
                targetClassName: $this->owningClass,
                targetPropertyName: $this->owningProperty,
                isSelfReferencing: $this->isSelfReferencing,
                isNullable: $this->isNullable,
                customReturnType: $this->customInverseReturnType
            )),
            default => throw new \InvalidArgumentException('Invalid type'),
        };
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getOwningClass(): string
    {
        return $this->owningClass;
    }

    public function getInverseClass(): string
    {
        return $this->inverseClass;
    }

    public function getOwningProperty(): string
    {
        return $this->owningProperty;
    }

    public function getInverseProperty(): string
    {
        return $this->inverseProperty;
    }

    public function isNullable(): bool
    {
        return $this->isNullable;
    }

    public function isSelfReferencing(): bool
    {
        return $this->isSelfReferencing;
    }

    public function getMapInverseRelation(): bool
    {
        return $this->mapInverseRelation;
    }

    public function setMapInverseRelation(bool $mapInverseRelation): void
    {
        if ($mapInverseRelation && $this->inverseProperty) {
            throw new \Exception('Cannot set setMapInverseRelation() to true when the inverse relation property is set.');
        }

        $this->mapInverseRelation = $mapInverseRelation;
    }
}
