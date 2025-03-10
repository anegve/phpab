<?php

declare(strict_types=1);

/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Analytics\DataCollector;

use InvalidArgumentException;
use PhpAb\Event\SubscriberInterface;
use PhpAb\Test\Bag;
use PhpAb\Test\TestInterface;
use PhpAb\Variant\VariantInterface;
use Webmozart\Assert\Assert;

/**
 * A data collector that holds information about which tests have been executed in a format for Google.
 *
 * @package PhpAb
 */
class Google implements SubscriberInterface
{
    public const EXPERIMENT_ID = 'experimentId';

    /**
     * @var array Test identifiers and variation indexes
     */
    private array $participations = [];

    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents(): array
    {
        return [
            'phpab.participation.variant_run' => function ($options) {
                Assert::notEmpty($options, 'Array passed to closure cannot be empty.');

                Assert::keyExists($options, 1, 'Second parameter passed to closure must be instance of Bag.');

                Assert::isInstanceOf(
                    $options[1],
                    Bag::class,
                    'Second parameter passed to closure must be instance of Bag.'
                );

                Assert::keyExists(
                    $options,
                    2,
                    'Third parameter passed to closure must be instance of VariantInterface.'
                );

                Assert::isInstanceOf(
                    $options[2],
                    VariantInterface::class,
                    'Third parameter passed to closure must be instance of VariantInterface.'
                );

                /** @var TestInterface $test */
                $test = $options[1]->getTest();

                Assert::keyExists(
                    $test->getOptions(),
                    static::EXPERIMENT_ID,
                    'A Google Analytics Experiment Id must be set as options.'
                );

                $experimentId = $test->getOptions()[static::EXPERIMENT_ID];

                /** @var VariantInterface $chosenVariant */
                $chosenVariant = $options[2];

                $variants = $test->getVariants();

                // Get the index number of the element
                $chosenIndex = array_search($chosenVariant->getIdentifier(), array_keys($variants));

                // Call the add method
                $this->addParticipation($experimentId, $chosenIndex);
            }
        ];
    }

    /**
     * Adds a participation to the data collector.
     *
     * @param string $testIdentifier It will look like "Qp0gahJ3RAO3DJ18b0XoUQ"
     * @param int $variationIndex
     * @throws InvalidArgumentException
     */
    public function addParticipation(string $testIdentifier, int $variationIndex): void
    {
        Assert::string($testIdentifier, 'Test identifier must be a string');
        Assert::integer($variationIndex, 'Variation index must be integer');
        Assert::greaterThan($variationIndex, -1, 'Variation index must be integer >= 0');

        $this->participations[$testIdentifier] = $variationIndex;
    }

    /**
     * Gets the test data that has been collected.
     *
     * @return array
     */
    public function getTestsData(): array
    {
        return $this->participations;
    }
}
