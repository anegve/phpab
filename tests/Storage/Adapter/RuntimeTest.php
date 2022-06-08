<?php

declare(strict_types=1);

/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Storage\Adapter;

use PHPUnit\Framework\TestCase;

class RuntimeTest extends TestCase
{
    /**
     * @covers Runtime::__construct
     */
    public function testConstructor(): void
    {
        // Arrange
        $storage = new Runtime();

        // Act
        $result = $storage->all();

        // Assert
        $this->assertIsArray($result);
    }

    /**
     * @covers Runtime::has
     */
    public function testHasWithValidEntry(): void
    {
        // Arrange
        $storage = new Runtime();
        $storage->set('identifier', 'participation');

        // Act
        $result = $storage->has('identifier');

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @covers Runtime::has
     */
    public function testHasWithInvalidEntry(): void
    {
        // Arrange
        $storage = new Runtime();

        // Act
        $result = $storage->has('identifier');

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @covers Runtime::has
     */
    public function testHasWithZeroEntry(): void
    {
        // Arrange
        $storage = new Runtime();
        $storage->set('0', 'participation');

        // Act
        $result = $storage->has('0');

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @covers Runtime::get
     */
    public function testGetValidEntry(): void
    {
        // Arrange
        $storage = new Runtime();
        $storage->set('identifier', 'participation');

        // Act
        $result = $storage->get('identifier');

        // Assert
        $this->assertEquals('participation', $result);
    }

    /**
     * @covers Runtime::get
     */
    public function testGetInvalidEntry(): void
    {
        // Arrange
        $storage = new Runtime();

        // Act
        $result = $storage->get('identifier');

        // Assert
        $this->assertNull($result);
    }

    /**
     * @covers Runtime::set
     */
    public function testSet(): void
    {
        // Arrange
        $storage = new Runtime();

        // Act
        $storage->set('identifier', 'participation');

        // Assert
        $this->assertEquals('participation', $storage->get('identifier'));
    }

    /**
     * @covers Runtime::all
     */
    public function testAllWithEmptyStorage(): void
    {
        // Arrange
        $storage = new Runtime();

        // Act
        $result = $storage->all();

        // Assert
        $this->assertEquals([], $result);
    }

    /**
     * @covers Runtime::all
     */
    public function testAllWithFilledStorage(): void
    {
        // Arrange
        $storage = new Runtime();
        $storage->set('identifier1', 'participation1');
        $storage->set('identifier2', 'participation2');

        // Act
        $result = $storage->all();

        // Assert
        $this->assertEquals(
            [
                'identifier1' => 'participation1',
                'identifier2' => 'participation2',
            ],
            $result
        );
    }

    /**
     * @covers Runtime::remove
     */
    public function testRemoveWithEmptyStorage(): void
    {
        // Arrange
        $storage = new Runtime();

        // Act
        $result = $storage->remove('identifier');

        // Assert
        $this->assertNull($result);
    }

    /**
     * @covers Runtime::remove
     */
    public function testRemoveWithFilledStorage(): void
    {
        // Arrange
        $storage = new Runtime();
        $storage->set('identifier', 'participation');

        // Act
        $result = $storage->remove('identifier');

        // Assert
        $this->assertEquals('participation', $result);
        $this->assertCount(0, $storage->all());
    }

    /**
     * @covers Runtime::clear
     */
    public function testClearEmptyStorage(): void
    {
        // Arrange
        $storage = new Runtime();

        // Act
        $result = $storage->clear();

        // Assert
        $this->assertEquals([], $result);
    }

    /**
     * @covers Runtime::clear
     */
    public function testClearFilledStorage(): void
    {
        // Arrange
        $storage = new Runtime();
        $storage->set('identifier1', 'participation1');
        $storage->set('identifier2', 'participation2');

        // Act
        $result = $storage->clear();

        // Assert
        $this->assertEquals(
            [
                'identifier1' => 'participation1',
                'identifier2' => 'participation2',
            ],
            $result
        );
        $this->assertCount(0, $storage->all());
    }
}
