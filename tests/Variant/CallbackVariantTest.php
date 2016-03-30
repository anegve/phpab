<?php

namespace PhpAb\Variant;

use PhpAb\Exception\TestExecutionException;

class CallbackVariantTest extends \PHPUnit_Framework_TestCase
{
    public function testGetIdentifier()
    {
        // Arrange
        $variant = new CallbackVariant('name', function () {});

        // Act
        $identifier = $variant->getIdentifier();

        // Assert
        $this->assertEquals('name', $identifier);
    }

    public function testRunWithSimpleClosure()
    {
        // Arrange
        $variant = new CallbackVariant('name', function () {
            return 'Walter';
        });

        // Act
        // Assert
        $this->assertEquals('Walter', $variant->run());
    }

    /**
     * @expectedException \PhpAb\Exception\TestExecutionException
     */
    public function testRunClosureThrowsException()
    {
        // Arrange
        $variant = new CallbackVariant('name', function () {
            throw new TestExecutionException();
        });

        // Act
        $variant->run();
    }
}
