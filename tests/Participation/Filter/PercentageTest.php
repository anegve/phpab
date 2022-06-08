<?php

declare(strict_types=1);

/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Participation\Filter;

use InvalidArgumentException;
use phpmock\functions\FixedValueFunction;
use phpmock\Mock;
use phpmock\MockBuilder;
use PHPUnit\Framework\TestCase;
use TypeError;

class PercentageTest extends TestCase
{
    public function testShouldParticipateWithFullPropability(): void
    {
        // Arrange
        $lottery = new Percentage(100);

        // Act
        $participates = $lottery->shouldParticipate();

        // Assert
        $this->assertTrue($participates);
    }

    public function testShouldParticipateWithZeroPropability(): void
    {
        // Arrange
        $lottery = new Percentage(0);

        // Act
        $participates = $lottery->shouldParticipate();

        // Assert
        $this->assertFalse($participates);
    }

    public function testShouldParticipateWithCustomPropabilityAndPositiveResult(): void
    {
        // Arrange
        // Override random_int
        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
            ->setName('random_int')
            ->setFunctionProvider(new FixedValueFunction(0));
        $mock = $builder->build();
        $mock->enable();

        $lottery = new Percentage(23);

        // Act
        $participates = $lottery->shouldParticipate();

        // Assert
        $this->assertTrue($participates);
    }

    public function testShouldParticipateWithCustomPropabilityAndNegativeResult(): void
    {
        // Arrange
        // Override random_int
        $builder = new MockBuilder();
        $builder->setNamespace(__NAMESPACE__)
            ->setName('random_int')
            ->setFunctionProvider(new FixedValueFunction(99));
        $mock = $builder->build();
        $mock->enable();

        $lottery = new Percentage(23);

        // Act
        $participates = $lottery->shouldParticipate();

        // Assert
        $this->assertFalse($participates);
    }

    public function testShouldParticipateWithOverPercentage(): void
    {
        $this->expectException(InvalidArgumentException::class);

        // Arrange
        $lottery = new Percentage(101);

        // Act
        $lottery->shouldParticipate();
    }

    public function testShouldAcceptIntergerOnly(): void
    {
        $this->expectException(TypeError::class);

        // Arrange
        $lottery = new Percentage('Walter');

        // Act
        $lottery->shouldParticipate();
    }

    public function testShouldParticipateWithUnderPercentage(): void
    {
        $this->expectException(InvalidArgumentException::class);

        // Arrange
        $lottery = new Percentage(-1);

        // Act
        $lottery->shouldParticipate();
    }

    public function tearDown(): void
    {
        // disable all mocked functions
        Mock::disableAll();
    }
}
