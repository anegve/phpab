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
 * Stores the participation state of the user only for the current request.
 *
 * @package PhpAb
 */
class Runtime implements AdapterInterface
{
    /**
     * @var array The data that has been set.
     */
    private array $data;

    /**
     * Initializes a new instance of this class.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $identifier): bool
    {
        return array_key_exists($identifier, $this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $identifier): ?string
    {
        if (!$this->has($identifier)) {
            return null;
        }

        return $this->data[$identifier];
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $identifier, ?string $value): bool
    {
        $this->data[$identifier] = $value;
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * {@inheritDoc}
     */
    public function remove(string $identifier): ?string
    {
        if (!$this->has($identifier)) {
            return null;
        }

        $removedValue = $this->data[$identifier];

        unset($this->data[$identifier]);

        return $removedValue;
    }

    /**
     * {@inheritDoc}
     */
    public function clear(): array
    {
        $removedValues = $this->data;

        $this->data = [];

        return $removedValues;
    }
}
