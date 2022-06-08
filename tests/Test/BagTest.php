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

use PhpAb\Participation\Filter\FilterInterface;
use PhpAb\Variant\Chooser\ChooserInterface;
use PHPUnit\Framework\TestCase;

class BagTest extends TestCase
{
    private $test;
    private $participationFilter;
    private $variantChooser;

    public function setUp(): void
    {
        $this->test = $this->createMock(TestInterface::class);
        $this->participationFilter = $this->createMock(FilterInterface::class);
        $this->variantChooser = $this->createMock(ChooserInterface::class);
    }

    public function testGetTest(): void
    {
        // Arrange
        $bag = new Bag($this->test, $this->participationFilter, $this->variantChooser, []);

        // Act
        $test = $bag->getTest();

        // Assert
        $this->assertInstanceOf(TestInterface::class, $test);
    }

    public function testGetOptions(): void
    {
        // Arrange
        $bag = new Bag($this->test, $this->participationFilter, $this->variantChooser, ['Walter']);

        // Act
        $options = $bag->getOptions();

        // Assert
        $this->assertEquals(['Walter'], $options);
    }

    public function testGetOptionsIfNotProvided(): void
    {
        // Arrange
        $bag = new Bag($this->test, $this->participationFilter, $this->variantChooser);

        // Act
        $options = $bag->getOptions();

        // Assert
        $this->assertEquals([], $options);
    }

    public function testGetParticipationFilter(): void
    {
        // Arrange
        $bag = new Bag($this->test, $this->participationFilter, $this->variantChooser);

        // Act
        $filter = $bag->getParticipationFilter();

        // Assert
        $this->assertInstanceOf(FilterInterface::class, $filter);
    }

    public function testGetVariantChooser(): void
    {
        // Arrange
        $bag = new Bag($this->test, $this->participationFilter, $this->variantChooser);

        // Act
        $chooser = $bag->getVariantChooser();

        // Assert
        $this->assertInstanceOf(ChooserInterface::class, $chooser);
    }
}
