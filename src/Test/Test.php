<?php

declare(strict_types=1);

/**
 * This file is part of phpab/phpab. (https://github.com/phpab/phpab)
 *
 * @link https://github.com/phpab/phpab for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab/master/LICENSE.md MIT
 */

namespace PhpAb\Test;

use InvalidArgumentException;
use PhpAb\Exception\DuplicateVariantException;
use PhpAb\Variant\VariantInterface;

/**
 * The implementation of a Test.
 *
 * @package PhpAb
 */
class Test implements TestInterface
{
    /**
     * The identifier of this test.
     *
     * @var string
     */
    private string $identifier;

    /**
     * The available variantns for this test.
     *
     * @var VariantInterface[]
     */
    private array $variants;

    /**
     * Case specific options
     *
     * @var array
     */
    private array $options;

    /**
     * Initializes a new instance of this class.
     *
     * @param string $identifier The identifier
     * @param VariantInterface[] $variants The variants that this test has.
     * @param array $options Case specific test options.
     */
    public function __construct(string $identifier, array $variants = [], array $options = [])
    {
        if ($identifier === '') {
            throw new InvalidArgumentException('The provided identifier is not a valid identifier.');
        }

        $this->identifier = $identifier;
        $this->setVariants($variants);
        $this->options = $options;
    }

    /**
     * {@inheritDoc}
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Adds a variant to this test.
     *
     * @throws DuplicateVariantException
     *
     * @param VariantInterface $variant The variant to add to this test.
     *
     * @return self
     */
    public function addVariant(VariantInterface $variant): self
    {
        if (array_key_exists($variant->getIdentifier(), $this->variants)) {
            throw new DuplicateVariantException('A variant with this identifier has already been added.');
        }

        $this->variants[$variant->getIdentifier()] = $variant;

        return $this;
    }

    /**
     * Sets the variants in this test.
     *
     * @param VariantInterface[] $variants The variants to set.
     *
     * @return self
     */
    public function setVariants(array $variants): self
    {
        $this->variants = [];

        foreach ($variants as $variant) {
            $this->addVariant($variant);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getVariants(): array
    {
        return $this->variants;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $identifier The identifier of the variant to get.
     */
    public function getVariant(string $identifier): ?VariantInterface
    {
        return $this->variants[$identifier] ?? null;
    }

    /**
     * Get the test options
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
