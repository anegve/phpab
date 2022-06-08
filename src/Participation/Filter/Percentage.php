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

/**
 * A filter that acts based on a percentage.
 *
 * @package PhpAb
 */
class Percentage implements FilterInterface
{
    /**
     * The chance of allowing a user to participate in a test.
     * This is a value between 0 and 100.
     *
     * @var int
     */
    private int $propability;

    /**
     * Initializes a new instance of this class.
     *
     * @param int $propability The probability for the lottery in percent.
     * Should be 0 <=> 100
     * 0 is lowest probability for participation
     * 100 is the highest probability for participation
     */
    public function __construct(int $propability)
    {
        if ($propability < 0 || $propability > 100) {
            throw new InvalidArgumentException('the probability must be 0 <=> 100');
        }

        $this->propability = $propability;
    }

    /**
     * {@inheritDoc}
     */
    public function shouldParticipate(): bool
    {
        $propability = $this->propability;

        if (100 === $propability) {
            return true;
        }

        if (0 === $propability) {
            // since we allow 0 as a value we have to check for it
            // to prevent division by zero error.
            return false;
        }

        return random_int(0, 100) <= $propability;
    }
}
