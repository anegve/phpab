<?php

namespace Phpab\Phpab\Engine;

use Phpab\Phpab\Analytics\AnalyticsInterface;
use Phpab\Phpab\Exception\TestCollisionException;
use Phpab\Phpab\Exception\TestNotFoundException;
use Phpab\Phpab\Participation\FilterInterface;
use Phpab\Phpab\Participation\StorageInterface;
use Phpab\Phpab\Test\TestInterface;
use Phpab\Phpab\Variant\ChooserInterface;

interface EngineInterface
{
    /**
     * Gets the storage where information about
     * the users participation is stored.
     *
     * @return StorageInterface
     */
    public function getStorage();

    /**
     * Get the Analytics instance which handles the Events
     * that occur during the test process.
     *
     * This is like a EventListener with limited API
     *
     * @return AnalyticsInterface
     */
    public function getAnalytics();

    /**
     * Get all tests for the engine
     *
     * @return TestInterface[]|array
     */
    public function getTests();

    /**
     * Get a test from the engine
     *
     * @param string $test The identifier of the test
     *
     * @throws TestNotFoundException
     *
     * @return TestInterface
     */
    public function getTest($test);

    /**
     * Adds a test to the Engine
     *
     * @param \Phpab\Phpab\Test\TestInterface                 $test
     * @param array                                           $options
     * @param \Phpab\Phpab\Participation\FilterInterface|null $participationFilter
     * @param \Phpab\Phpab\Variant\ChooserInterface|null      $variantChooser
     *
     * @throws TestCollisionException
     *
     * @return null
     */
    public function addTest(
        TestInterface $test,
        $options = [],
        FilterInterface $participationFilter = null,
        ChooserInterface $variantChooser = null
    );

    /**
     * Starts the tests
     *
     * @return null
     */
    public function start();
}
