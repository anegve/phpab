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

use Laminas\EventManager\EventManager;
use PHPUnit\Framework\TestCase;

class LaminasDispatcherTest extends TestCase
{
    public function testGetEventManager(): void
    {
        // Arrange
        $eventManager = new EventManager();
        $dispatcher = new LaminasDispatcher($eventManager);

        // Act
        $result = $dispatcher->getEventManager();

        // Assert
        $this->assertEquals($eventManager, $result);
    }

    public function testDispatch(): void
    {
        // Arrange
        $eventManager = $this->createMock(EventManager::class);
        $dispatcher = new LaminasDispatcher($eventManager);

        // Assert
        $eventManager->expects($this->once())->method('trigger')->with(
            $this->equalTo('event.foo'),
            $this->equalTo($dispatcher),
            $this->equalTo([])
        );

        // Act
        $dispatcher->dispatch('event.foo', []);
    }
}
