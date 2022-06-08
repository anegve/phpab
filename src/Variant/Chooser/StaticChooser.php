<?php

declare(strict_types=1);

/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Variant\Chooser;

use PhpAb\Variant\VariantInterface;

/**
 * A static choice implementation. The choice has been set by default already.
 *
 * @package PhpAb
 */
class StaticChooser implements ChooserInterface
{
    /**
     * The index of the variant to use.
     *
     * @var mixed
     */
    private mixed $choice;

    /**
     * Initializes a new instance of this class.
     *
     * @param mixed $choice
     */
    public function __construct(mixed $choice)
    {
        $this->choice = $choice;
    }

    /**
     * {@inheritDoc}
     *
     * @param VariantInterface[] $variants Variants to choose from
     */
    public function chooseVariant(array $variants): ?VariantInterface
    {
        return $variants[$this->choice] ?? null;
    }
}
