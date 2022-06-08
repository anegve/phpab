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

use PhpAb\Storage\Adapter\AdapterInterface;

/**
 * {@inheritDoc}
 */
class Storage implements StorageInterface
{
    /**
     * @var AdapterInterface
     */
    private AdapterInterface $adapter;

    /**
     * @param AdapterInterface $adapterInterface
     */
    public function __construct(AdapterInterface $adapterInterface)
    {
        $this->adapter = $adapterInterface;
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $identifier): bool
    {
        return $this->adapter->has($identifier);
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $identifier)
    {
        return $this->adapter->get($identifier);
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $identifier, mixed $participation)
    {
        $this->adapter->set($identifier, $participation);
    }

    /**
     * {@inheritDoc}
     */
    public function clear(): array
    {
        return $this->adapter->clear();
    }
}
