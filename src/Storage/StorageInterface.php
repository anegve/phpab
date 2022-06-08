<?php

declare(strict_types=1);

/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Storage;

use InvalidArgumentException;

/**
 * Stores the participation state of the user
 *
 * @package PhpAb
 */
interface StorageInterface
{
    /**
     * Checks if the test has a participation set.
     *
     * @param string $identifier The tests identifier
     * @return bool true if the test participation is defined, false otherwise
     * @throws InvalidArgumentException
     */
    public function has(string $identifier): bool;

    /**
     * Returns the participation value (Variant or false).
     *
     * @param string $identifier The tests identifier name
     * @return string|null
     *
     * @throws InvalidArgumentException
     */
    public function get(string $identifier): ?string;

    /**
     * Sets participation value for a test
     *
     * @param string $identifier The tests identifier
     * @param string|null $participation The participated variant
     *
     * @throws InvalidArgumentException
     */
    public function set(string $identifier, ?string $participation): void;

    /**
     * Clears out state for a test.
     *
     * @return mixed Whatever data was contained.
     */
    public function clear(): mixed;
}
