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

use PhpAb\Exception\TestException;

/**
 * The interface that should be implemented by all variants.
 *
 * @package PhpAb
 */
interface VariantInterface
{
    /**
     * Gets the Identifier for the variant.
     * This will be stored in storage for participating users.
     *
     * @return string
     */
    public function getIdentifier(): string;

    /**
     * Run the variant
     *
     * @throws TestException
     */
    public function run(): void;
}
