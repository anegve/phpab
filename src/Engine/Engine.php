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

use PhpAb\Event\DispatcherInterface;
use PhpAb\Exception\EngineLockedException;
use PhpAb\Exception\TestCollisionException;
use PhpAb\Exception\TestNotFoundException;
use PhpAb\Participation\Filter\FilterInterface;
use PhpAb\Participation\ManagerInterface;
use PhpAb\Test\Bag;
use PhpAb\Test\TestInterface;
use PhpAb\Variant\Chooser\ChooserInterface;
use PhpAb\Variant\VariantInterface;
use Webmozart\Assert\Assert;

/**
 * The engine used to start tests.
 *
 * @package PhpAb
 */
class Engine implements EngineInterface
{
    /**
     * A list with test bags.
     *
     * @var Bag[]
     */
    public array $tests = [];

    /**
     * The participation manager used to check if a user particiaptes.
     *
     * @var ManagerInterface
     */
    private ManagerInterface $participationManager;

    /**
     * The event dispatcher that dispatches events related to tests.
     *
     * @var DispatcherInterface
     */
    private DispatcherInterface $dispatcher;

    /**
     * The default filter that is used when a test bag has no filter set.
     *
     * @var FilterInterface|null
     */
    private ?FilterInterface $filter;

    /**
     * The default variant chooser that is used when a test bag has no variant chooser set.
     *
     * @var ChooserInterface|null
     */
    private ?ChooserInterface $chooser;

    /**
     * Locks the engine for further manipulaton
     *
     * @var boolean
     */
    private bool $locked = false;

    /**
     * Initializes a new instance of this class.
     *
     * @param ManagerInterface $participationManager Handles the Participation state
     * @param DispatcherInterface $dispatcher Dispatches events
     * @param FilterInterface|null $filter The default filter to use if no filter is provided for the test.
     * @param ChooserInterface|null $chooser The default chooser to use if no chooser is provided for the test.
     */
    public function __construct(
        ManagerInterface $participationManager,
        DispatcherInterface $dispatcher,
        ?FilterInterface $filter = null,
        ?ChooserInterface $chooser = null
    ) {

        $this->participationManager = $participationManager;
        $this->dispatcher = $dispatcher;
        $this->filter = $filter;
        $this->chooser = $chooser;
    }

    /**
     * {@inheritDoc}
     */
    public function getTests(): array
    {
        $tests = [];
        foreach ($this->tests as $bag) {
            $tests[] = $bag->getTest();
        }

        return $tests;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $test The identifier of the test
     */
    public function getTest(string $test): TestInterface
    {
        if (! isset($this->tests[$test])) {
            throw new TestNotFoundException('No test with identifier '.$test.' found');
        }

        return $this->tests[$test]->getTest();
    }

    /**
     * {@inheritDoc}
     *
     * @param TestInterface $test
     * @param array $options
     * @param FilterInterface|null $filter
     * @param ChooserInterface|null $chooser
     */
    public function addTest(
        TestInterface $test,
        array $options = [],
        ?FilterInterface $filter = null,
        ?ChooserInterface $chooser = null
    ): self {

        if ($this->locked) {
            throw new EngineLockedException('The engine has been processed already. You cannot add other tests.');
        }

        if (isset($this->tests[$test->getIdentifier()])) {
            throw new TestCollisionException('Duplicate test for identifier '.$test->getIdentifier());
        }

        // If no filter/chooser is set use the ones from
        // the engine.
        $filter = $filter ?: $this->filter;
        $chooser = $chooser ?: $this->chooser;

        Assert::notNull($filter, 'There must be at least one filter in the Engine or in the TestBag');
        Assert::notNull($chooser, 'There must be at least one chooser in the Engine or in the TestBag');

        $this->tests[$test->getIdentifier()] = new Bag($test, $filter, $chooser, $options);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function start(): void
    {
        // Check if already locked
        if ($this->locked) {
            throw new EngineLockedException('The engine is already locked and could not be started once again.');
        }

        // Lock the engine for further manipulation
        $this->locked = true;

        foreach ($this->tests as $testBag) {
            $this->handleTestBag($testBag);
        }
    }

    /**
     * Process the test bag
     *
     * @param Bag $bag
     *
     * @return void true if the variant got executed, false otherwise
     */
    private function handleTestBag(Bag $bag): void
    {
        $test = $bag->getTest();

        $isParticipating = $this->participationManager->participates($test->getIdentifier());
        $testParticipation = $this->participationManager->getParticipatingVariant($test->getIdentifier());

        // Check if the user is marked as "do not participate".
        if ($isParticipating && null === $testParticipation) {
            $this->dispatcher->dispatch('phpab.participation.blocked', [$this, $bag]);
            return;
        }

        // When the user does not participate at the test, let him participate.
        if (!$isParticipating && !$bag->getParticipationFilter()->shouldParticipate()) {
            // The user should not participate so let's set participation
            // to null so he will not participate in the future, too.
            $this->dispatcher->dispatch('phpab.participation.block', [$this, $bag]);

            $this->participationManager->participate($test->getIdentifier(), null);
            return;
        }

        // Let's try to recover a previously stored Variant
        if ($isParticipating && $testParticipation !== null) {
            $variant = $bag->getTest()->getVariant($testParticipation);

            // If we managed to identify a Variant by a previously stored participation, do its magic again.
            if ($variant instanceof VariantInterface) {
                $this->activateVariant($bag, $variant);
                return;
            }
        }

        // Choose a variant for later usage. If the user should participate this one will be used
        $chosen = $bag->getVariantChooser()->chooseVariant($test->getVariants());

        // Check if user participation should be blocked. Or maybe the variant does not exists anymore?
        if (null === $chosen || !$test->getVariant($chosen->getIdentifier())) {
            $this->dispatcher->dispatch('phpab.participation.variant_missing', [$this, $bag]);

            $this->participationManager->participate($test->getIdentifier(), null);
            return;
        }

        // Store the chosen variant so he will not switch between different states
        $this->participationManager->participate($test->getIdentifier(), $chosen->getIdentifier());

        $this->activateVariant($bag, $chosen);
    }

    /**
     * Runs the Variant and dispatches subscriptions
     *
     * @param Bag $bag
     * @param VariantInterface $variant
     */
    private function activateVariant(Bag $bag, VariantInterface $variant): void
    {
        $this->dispatcher->dispatch('phpab.participation.variant_run', [$this, $bag, $variant]);

        $variant->run();
    }
}
