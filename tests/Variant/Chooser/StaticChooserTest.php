<?php

declare(strict_types=1);

/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Variant\Chooser;

use PhpAb\Variant\SimpleVariant;
use PHPUnit\Framework\TestCase;

class StaticChooserTest extends TestCase
{
    public function testChooseStatic(): void
    {
        // Arrange
        $chooser = new StaticChooser(3);

        // Act
        $result = $chooser->chooseVariant(
            [
                new SimpleVariant('1'),
                new SimpleVariant('2'),
                new SimpleVariant('3'),
                new SimpleVariant('4'),
                new SimpleVariant('5'),
                new SimpleVariant('6'),
            ]
        );

        // Assert
        $this->assertEquals(new SimpleVariant('4'), $result);
    }

    public function testChooseStaticFails(): void
    {
        // Arrange
        $chooser = new StaticChooser('3');

        // Act
        $result = $chooser->chooseVariant(
            [
                new SimpleVariant('1'),
                new SimpleVariant('2'),
            ]
        );

        // Assert
        $this->assertNull($result);
    }
}
