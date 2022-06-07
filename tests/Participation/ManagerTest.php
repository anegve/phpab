<?php
/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Participation;

use PhpAb\Storage\Adapter\Runtime;
use PhpAb\Storage\Storage;
use PhpAb\Test\Test;
use PhpAb\Variant\SimpleVariant;
use PHPUnit\Framework\TestCase;

class ManagerTest extends TestCase
{
    private $storage;

    public function setUp(): void
    {
        $this->storage = new Storage(new Runtime());
    }

    public function testCheckParticipation(): void
    {
        // Arrange
        $manager = new Manager($this->storage);

        // Act
        $result = $manager->participates('foo');

        // Assert
        $this->assertFalse($result);
    }

    public function testCheckParticipatesTestSuccess(): void
    {
        // Arrange
        $manager = new Manager($this->storage);
        $manager->participate('foo', 'bar');

        // Act
        $result = $manager->participates('foo');

        // Assert
        $this->assertTrue($result);
    }

    public function testCheckParticipatesTestObjectSuccess(): void
    {
        // Arrange
        $manager = new Manager($this->storage);
        $manager->participate(new Test('foo'), null);

        // Act
        $result = $manager->participates('foo');

        // Assert
        $this->assertTrue($result);
    }

    public function testCheckParticipatesTestVariantObjectSuccess(): void
    {
        // Arrange
        $manager = new Manager($this->storage);
        $manager->participate(new Test('foo'), new SimpleVariant('bar'));

        // Act
        $result = $manager->participates('foo', 'bar');

        // Assert
        $this->assertTrue($result);
    }

    public function testCheckParticipatesVariantSuccess(): void
    {
        // Arrange
        $manager = new Manager($this->storage);
        $manager->participate('foo', 'bar');

        // Act
        $result = $manager->participates('foo', 'bar');

        // Assert
        $this->assertTrue($result);
    }

    public function testCheckParticipatesVariantFail(): void
    {
        // Arrange
        $manager = new Manager($this->storage);
        $manager->participate('foo', 'yolo');

        // Act
        $result = $manager->participates('foo', 'bar');

        // Assert
        $this->assertFalse($result);
    }

    // More to come

    public function tearDown(): void
    {
        $this->storage->clear();
    }
}
