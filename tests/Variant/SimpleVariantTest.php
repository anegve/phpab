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

class SimpleVariantTest extends TestCase
{
    public function testGetIdentifier(): void
    {
        // Arrange
        $variant = new SimpleVariant('name');

        // Act
        $identifier = $variant->getIdentifier();

        // Assert
        $this->assertEquals('name', $identifier);
    }


    public function testRunReturnsNull(): void
    {
        // Arrange
        $variant = new SimpleVariant('name');

        // Act
        $result = $variant->run();

        // Assert
        $this->assertNull($result);
    }
}
