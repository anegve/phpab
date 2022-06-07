<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Variant\Chooser;

use PHPUnit\Framework\TestCase;

class StaticChooserTest extends TestCase
{
    public function testChooseStatic(): void
    {
        // Arrange
        $chooser = new StaticChooser(3);

        // Act
        $result = $chooser->chooseVariant([1, 2, 3, 4, 5, 6]);

        // Assert
        $this->assertEquals(4, $result);
    }

    public function testChooseStaticFails(): void
    {
        // Arrange
        $chooser = new StaticChooser(3);

        // Act
        $result = $chooser->chooseVariant([1, 2]);

        // Assert
        $this->assertNull($result);
    }
}
