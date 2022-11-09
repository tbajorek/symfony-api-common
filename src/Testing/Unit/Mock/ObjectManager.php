<?php declare(strict_types=1);

namespace ApiCommon\Testing\Unit\Mock;

use ApiCommon\Testing\Unit\Exception\MockException;
use ApiCommon\Testing\Unit\Exception\ReflectionException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException as BaseReflectionException;
use ReflectionParameter;

/**
 * @codeCoverageIgnore
 */
class ObjectManager
{
    private TestCase $testCase;
    private ReflectionGetter $reflectionGetter;

    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
        $this->reflectionGetter = new ReflectionGetter();
    }

    /**
     * @param string $className
     * @param array $arguments
     * @return array
     * @throws MockException
     * @throws ReflectionException
     */
    public function getConstructorArguments(string $className, array $arguments = []): array
    {
        $constructorArguments = [];
        $constructorParameters = $this->reflectionGetter->getConstructorParameters($className);
        foreach ($constructorParameters as $constructorParameter) {
            $parameterName = $constructorParameter->getName();

            if (array_key_exists($parameterName, $arguments)) {
                $constructorArguments[$parameterName] = $arguments[$parameterName];
                continue;
            }

            $defaultValue = null;
            if ($constructorParameter->isDefaultValueAvailable()) {
                $defaultValue = $constructorParameter->getDefaultValue();
            }

            $calculatedValue = null;
            if ($parameterClass = $this->reflectionGetter->getParameterClassReflection($constructorParameter)) {
                $parameterClassName = $parameterClass->getName();
                $calculatedValue = $this->getBasicMock($parameterClassName);
            } else {
                $calculatedValue = $this->getBuiltInParameterMock($constructorParameter);
            }
            $constructorArguments[$parameterName] = $calculatedValue ?? $defaultValue;
            if ($constructorArguments[$parameterName] === null && !$constructorParameter->allowsNull()) {
                throw new MockException(
                    sprintf('Argument %s of class %s can not be determined', $parameterName, $className)
                );
            }
        }
        return $constructorArguments;
    }

    /**
     * @param string $className
     * @param array $arguments
     * @return MockObject
     * @throws MockException
     * @throws ReflectionException
     */
    public function getFullMock(string $className, array $arguments = []): MockObject
    {
        $constructorArguments = $this->getConstructorArguments($className, $arguments);
        try {
            return $this->reflectionGetter->getClassReflection($className)->newInstanceArgs($constructorArguments);
        } catch (BaseReflectionException $reflectionException) {
            throw new ReflectionException($reflectionException->getMessage());
        }
    }

    public function getBasicMock(string $className): MockObject
    {
        return $this->testCase->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();
    }

    /**
     * @param ReflectionParameter $parameter
     * @return mixed
     * @throws MockException
     */
    private function getBuiltInParameterMock(ReflectionParameter $parameter): mixed
    {
        $parameterType = $this->reflectionGetter->getParameterTypeReflection($parameter);
        return match ($parameterType) {
            'array' => [],
            'string' => '',
            'int' => 0,
            'float', 'double' => 0.0,
            'bool' => false,
            'callable' => static function () {},
            default => throw new MockException(
                sprintf(
                    'Default value of parameter %s with type %s can not be calculated',
                    $parameter->getName(),
                    $parameterType
                )
            ),
        };
    }
}