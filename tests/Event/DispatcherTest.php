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

use PHPUnit\Framework\TestCase;
use stdClass;

class DispatcherTest extends TestCase
{
    public function testDispatchEventWithoutListeners(): void
    {
        // Arrange
        $dispatcher = new Dispatcher();

        // Act
        $result1 = $dispatcher->dispatch('event', null);
        $result2 = $dispatcher->dispatch('event2', null);

        // Assert
        $this->assertNull($result1);
        $this->assertNull($result2);
    }

    public function testDispatchWithSingleListener(): void
    {
        // Arrange
        $dispatcher = new Dispatcher();
        $dispatcher->addListener('event.foo', function ($subject) {
            $subject->executed = true;
            return 'yolo';
        });

        $subject = new stdClass();

        // Act
        $result = $dispatcher->dispatch('event.foo', $subject);

        // Assert
        $this->assertNull($result);
        $this->assertTrue($subject->executed);
    }

    public function testDispatchSubscriberNotAllDispatched(): void
    {
        // Arrange
        $callable = function ($subject) {
            $subject->touched++;
        };

        $subscriber = $this->createMock(SubscriberInterface::class);
        $subscriber
            ->method('getSubscribedEvents')
            ->willReturn(
                [
                    'event.foo' => $callable,
                    'event.bar' => $callable,
                ]
            );

        $dispatcher = new Dispatcher();
        $dispatcher->addSubscriber($subscriber);

        $subject = new stdClass();
        $subject->touched = 0;

        // Act
        $dispatcher->dispatch('event.foo', $subject);

        // Assert
        $this->assertEquals(1, $subject->touched);
    }

    public function testDispatchWithMultipleListenersOnOneEvent(): void
    {
        // Arrange
        $dispatcher = new Dispatcher();
        $dispatcher->addListener('event.foo', function ($subject) {
            $subject->touched++;
        });

        $dispatcher->addListener('event.foo', function ($subject) {
            $subject->touched++;
        });

        $subject = new stdClass();
        $subject->touched = 0;

        // Act
        $dispatcher->dispatch('event.foo', $subject);

        // Assert
        $this->assertEquals(2, $subject->touched);
    }

    public function testDispatchMultipleEvents(): void
    {
        // Arrange
        $dispatcher = new Dispatcher();
        $dispatcher->addListener('event.foo', function ($subject) {
            $subject->touched++;
        });

        $dispatcher->addListener('event.bar', function ($subject) {
            $subject->touched++;
        });

        $subject = new stdClass();
        $subject->touched = 0;

        // Act
        $dispatcher->dispatch('event.foo', $subject);
        $dispatcher->dispatch('event.bar', $subject);

        // Assert
        $this->assertEquals(2, $subject->touched);
    }
}
