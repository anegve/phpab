<?php

declare(strict_types=1);

/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Storage\Adapter;

/**
 * Used by StorageInterface to store user participations state
 *
 * @package PhpAb
 */
interface AdapterInterface
{
    /**
     * Returns if a string identified element exists
     *
     * @param string $identifier Element identifier
     *
     * @return bool If element exists
     */
    public function has(string $identifier): bool;

    /**
     * Returns the value of a string identified element
     *
     * @param string $identifier Element identifier
     *
     * @return string|null The value of element
     */
    public function get(string $identifier): ?string;

    /**
     * Sets the value of a string identified element
     *
     * @param string $identifier Element identifier
     * @param string|null $value Value of element to be set
     *
     * @return bool If element has been successfully set
     */
    public function set(string $identifier, ?string $value): bool;

    /**
     * Returns the content of all the elements
     *
     * @return array Content of all elements
     */
    public function all(): array;

    /**
     * Remove the content of a string identified element
     *
     * @param string $identifier Element identifier
     *
     * @return string|null value that has been removed
     */
    public function remove(string $identifier): ?string;

    /**
     * Clears all the elements
     *
     * @return array All the content that has been cleared
     */
    public function clear(): array;
}
