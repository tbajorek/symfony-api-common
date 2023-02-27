<?php declare(strict_types=1);

namespace ApiCommon\Testing\Unit\Mock;

use ApiCommon\Testing\Unit\Exception\ReflectionException;
use Reflection;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

/**
 * @codeCoverageIgnore
 */
class ReflectionGetter
{
    /**
     * @param string $className
     * @return ReflectionParameter[]
     * @throws ReflectionException
     */
    public function getConstructorParameters(string $className): array
    {
        $constructorReflection = $this->getConstructorReflection($className);
        if ($constructorReflection === null) {
            return [];
        }
        return $constructorReflection->getParameters();
    }

    /**
     * @param string $className
     * @return ReflectionMethod|null
     * @throws ReflectionException
     */
    public function getConstructorReflection(string $className): ?ReflectionMethod
    {
        $classReflection = $this->getClassReflection($className);
        $constructorReflection = $classReflection->getConstructor();
        if ($constructorReflection === null) {
            return null;
        }
        $constructorModifiers = Reflection::getModifierNames($constructorReflection->getModifiers());
        if (array_intersect($constructorModifiers, ['private', 'protected', 'abstract']) !== []) {
            throw new ReflectionException(
                sprintf(
                    'Constructor of %s class is not accessible',
                    $className
                )
            );
        }
        return $constructorReflection;
    }

    /**
     * @param string $className
     * @return ReflectionClass
     * @throws ReflectionException
     */
    public function getClassReflection(string $className): ReflectionClass
    {
        if (!class_exists($className)) {
            throw new ReflectionException(
                sprintf('Class %s does not exist', $className)
            );
        }
        return new ReflectionClass($className);
    }

    public function getParameterClassReflection(ReflectionParameter $reflectionParameter): ?ReflectionClass
    {
        $parameterType = $reflectionParameter->getType();
        if ($parameterType !== null && method_exists($parameterType, 'isBuiltin') === false) {
            return null;
        }
        return $parameterType && !$parameterType->isBuiltin()
            ? new ReflectionClass($parameterType->getName())
            : null;
    }

    public function getParameterTypeReflection(ReflectionParameter $reflectionParameter): ?string
    {
        $parameterType = $reflectionParameter->getType();
        if ($parameterType !== null && method_exists($parameterType, 'isBuiltin') === false) {
            return null;
        }
        return $parameterType && !$parameterType->isBuiltin()
            ? $parameterType->getName()
            : null;
    }
}