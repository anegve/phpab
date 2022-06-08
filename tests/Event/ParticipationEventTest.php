<?php

declare(strict_types=1);

/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Event;

use PhpAb\Test\TestInterface;
use PhpAb\Variant\VariantInterface;
use PHPUnit\Framework\TestCase;

class ParticipationEventTest extends TestCase
{
    private $test;
    private $variant;

    public function setUp(): void
    {
        $this->test = $this->createMock(TestInterface::class);
        $this->variant = $this->createMock(VariantInterface::class);
    }

    public function testGetTest(): void
    {
        // Arrange
        $event = new ParticipationEvent($this->test, $this->variant, false);

        // Act
        $result = $event->getTest();

        // Assert
        $this->assertSame($this->test, $result);
    }

    public function testGetVariant(): void
    {
        // Arrange
        $event = new ParticipationEvent($this->test, $this->variant, false);

        // Act
        $result = $event->getVariant();

        // Assert
        $this->assertSame($this->variant, $result);
    }

    public function testIsNotNew(): void
    {
        // Arrange
        $event = new ParticipationEvent($this->test, $this->variant, false);

        // Act
        $result = $event->isNew();

        // Assert
        $this->assertFalse($result);
    }

    public function testIsNew(): void
    {
        // Arrange
        $event = new ParticipationEvent($this->test, $this->variant, true);

        // Act
        $result = $event->isNew();

        // Assert
        $this->assertTrue($result);
    }
}
