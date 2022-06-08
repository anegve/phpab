<?php

declare(strict_types=1);

/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Variant;

use PHPUnit\Framework\TestCase;
use RuntimeException;

class CallbackVariantTest extends TestCase
{
    public function testGetIdentifier(): void
    {
        // Arrange
        $variant = new CallbackVariant('name', function () {
        });

        // Act
        $identifier = $variant->getIdentifier();

        // Assert
        $this->assertEquals('name', $identifier);
    }

    public function testRunExecutesCallback(): void
    {
        // Arrange
        $action = null;
        $variant = new CallbackVariant('name', function () use ($action) {
            $action = 'Walter';
            return $action;
        });

        // Act
        // Assert
        $this->assertNull($variant->run());
    }

    public function testRunClosureThrowsException(): void
    {
        $this->expectException(RuntimeException::class);

        // Arrange
        $variant = new CallbackVariant('name', function () {
            throw new RuntimeException;
        });

        // Act
        $variant->run();
    }

    public function testRunReturnsNull(): void
    {
        // Arrange
        $variant = new CallbackVariant('name', function () {
            return 'Walter';
        });

        // Act
        $result = $variant->run();

        // Assert
        $this->assertNull($result);
    }
}
