<?php declare(strict_types=1);

namespace ApiCommon\Testing\Unit;

use ApiCommon\Testing\Unit\Mock\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    protected ObjectManager $objectManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->objectManager = new ObjectManager($this);
    }

    protected function basicMock(string $className): MockObject
    {
        return $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->getMock();
    }
}