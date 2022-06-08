<?php

declare(strict_types=1);

/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Variant;

/**
 * The representation of a variant that invokes a callback when ran.
 *
 * @package PhpAb
 */
class CallbackVariant implements VariantInterface
{
    /**
     * The identifier of this variant.
     *
     * @var string
     */
    private string $identifier;

    /**
     * The callback that should be invoked when this variant is ran.
     *
     * @var callable
     */
    private $callback;

    /**
     * Initializes a new instance of this class.
     *
     * @param string $identifier The Identifier of the Variant
     * @param callable $callback The Callable to execute on run
     */
    public function __construct(string $identifier, callable $callback)
    {
        $this->identifier = $identifier;
        $this->callback = $callback;
    }

    /**
     * {@inheritDoc}
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * {@inheritDoc}
     */
    public function run(): void
    {
        call_user_func($this->callback);
    }
}
