<?php

declare(strict_types=1);

/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Engine;

use PhpAb\Exception\TestCollisionException;
use PhpAb\Exception\TestNotFoundException;
use PhpAb\Participation\Filter\FilterInterface;
use PhpAb\Test\TestInterface;
use PhpAb\Variant\Chooser\ChooserInterface;

/**
 * The interface that should be implemented by the engine.
 *
 * @package PhpAb
 */
interface EngineInterface
{
    /**
     * Get all tests for the engine
     *
     * @return TestInterface[]|array
     */
    public function getTests(): array;

    /**
     * Get a test from the engine
     *
     * @param string $test The identifier of the test
     * @return TestInterface
     *@throws TestNotFoundException Thrown when the requested test does not exists.
     */
    public function getTest(string $test): TestInterface;

    /**
     * Adds a test to the Engine
     *
     * @param TestInterface $test
     * @param array $options
     * @param FilterInterface|null $filter
     * @param ChooserInterface|null $chooser
     * @throws TestCollisionException Thrown when the test already exists.
     */
    public function addTest(
        TestInterface $test,
        array $options = [],
        ?FilterInterface $filter = null,
        ?ChooserInterface $chooser = null
    );

    /**
     * Starts the tests
     *
     * @return void
     */
    public function start(): void;
}
