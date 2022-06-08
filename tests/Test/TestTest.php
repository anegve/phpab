<?php

declare(strict_types=1);

/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Test;

use InvalidArgumentException;
use PhpAb\Exception\DuplicateVariantException;
use PhpAb\Variant\SimpleVariant;
use PHPUnit\Framework\TestCase;
use TypeError;

class TestTest extends TestCase
{
    /**
     * @covers Test::__construct
     * @covers Test::getIdentifier
     */
    public function testConstructorAndGetIdentifierWithValidIdentifier(): void
    {
        // Arrange
        $test = new Test('identifier');

        // Act
        $result = $test->getIdentifier();

        // Assert
        $this->assertEquals('identifier', $result);
    }

    /**
     * @covers Test::__construct
     * @covers Test::getIdentifier
     */
    public function testConstructorAndGetIdentifierWithInvalidIdentifier(): void
    {
        $this->expectException(TypeError::class);

        // Arrange
        // ...

        // Act
        $test = new Test(null);

        // Assert
        // ...
    }

    /**
     * @covers Test::__construct
     * @covers Test::getVariants
     */
    public function testConstructorAndGetVariantsWithVariants(): void
    {
        // Arrange
        $variant = new SimpleVariant('identifier');
        $test = new Test('identifier', [
            $variant,
        ]);

        // Act
        $result = $test->getVariants();

        // Assert
        $this->assertEquals(
            [
                'identifier' => $variant
            ],
            $result
        );
    }

    /**
     * @covers Test::addVariant
     */
    public function testAddVariant(): void
    {
        // Arrange
        $variant = new SimpleVariant('identifier');
        $test = new Test('identifier');

        // Act
        $test->addVariant($variant);

        // Assert
        $this->assertEquals(
            [
                'identifier' => $variant
            ],
            $test->getVariants()
        );
    }

    /**
     * @covers Test::addVariant
     */
    public function testAddVariantWithDuplicateIdentifier(): void
    {
        $this->expectExceptionMessage("A variant with this identifier has already been added.");
        $this->expectException(DuplicateVariantException::class);

        // Arrange
        $variant = new SimpleVariant('identifier');
        $test = new Test('identifier');

        // Act
        $test->addVariant($variant);
        $test->addVariant($variant);

        // Assert
        // ...
    }

    /**
     * @covers Test::setVariants
     */
    public function testSetVariantsWithEmptyArray(): void
    {
        // Arrange
        $test = new Test('identifier');

        // Act
        $test->setVariants([]);

        // Assert
        $this->assertEquals([], $test->getVariants());
    }

    /**
     * @covers Test::setVariants
     */
    public function testSetVariantsWithSingleVariant(): void
    {
        // Arrange
        $variant1 = new SimpleVariant('identifier1');
        $test = new Test('identifier');

        // Act
        $test->setVariants([$variant1]);

        // Assert
        $this->assertEquals(
            [
                'identifier1' => $variant1,
            ],
            $test->getVariants()
        );
    }

    /**
     * @covers Test::setVariants
     */
    public function testSetVariantsWithMultipleVariant(): void
    {
        // Arrange
        $variant1 = new SimpleVariant('identifier1');
        $variant2 = new SimpleVariant('identifier2');
        $test = new Test('identifier');

        // Act
        $test->setVariants([$variant1, $variant2]);

        // Assert
        $this->assertEquals(
            [
                'identifier1' => $variant1,
                'identifier2' => $variant2,
            ],
            $test->getVariants()
        );
    }

    /**
     * @covers Test::setVariants
     */
    public function testSetVariantsWithDuplicateVariants(): void
    {
        $this->expectExceptionMessage("A variant with this identifier has already been added.");
        $this->expectException(DuplicateVariantException::class);

        // Arrange
        $variant1 = new SimpleVariant('identifier1');
        $variant2 = new SimpleVariant('identifier1');
        $test = new Test('identifier');

        // Act
        $test->setVariants([$variant1, $variant2]);

        // Assert
        // ...
    }

    /**
     * @covers Test::getVariant
     */
    public function testGetVariant(): void
    {
        // Arrange
        $variant1 = new SimpleVariant('identifier1');
        $test = new Test('identifier', [$variant1]);

        // Act
        $result = $test->getVariant('identifier1');

        // Assert
        $this->assertEquals($variant1, $result);
    }

    /**
     * @covers Test::getVariant
     */
    public function testGetVariantWithInvalidIdentifier(): void
    {
        // Arrange
        $test = new Test('identifier');

        // Act
        $result = $test->getVariant('identifier1');

        // Assert
        $this->assertNull($result);
    }

    /**
     * Testing that options passed in constructor are returned by getOptions
     */
    public function testGetOptions(): void
    {
        // Arrange
        $options = [
            'key1' => 'val1',
            'key2' => 'val2'
        ];

        $test = new Test('identifier', [], $options);

        // Act
        $result = $test->getOptions();

        // Assert
        $this->assertSame($options, $result);
    }
}
