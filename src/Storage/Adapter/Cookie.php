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

use InvalidArgumentException;
use RuntimeException;
use Webmozart\Assert\Assert;

/**
 * Cookie Storage Adapter records the participation state of the user in a cookie.
 *
 * @package PhpAb
 */
class Cookie implements AdapterInterface
{
    /**
     * The name of cookie.
     *
     * @var string
     */
    protected string $cookieName;
    /**
     * The cookie's time to live in seconds
     *
     * @var int
     */
    protected int $ttl;
    /**
     * The array of which will be saved in cookie.
     *
     * @var null|array
     */
    protected ?array $data = null;

    /**
     * Initializes a new instance of this class.
     *
     * @param string $cookieName   The name the cookie.
     * @param int $ttl           How long should the cookie last in browser. Default 5 years
     *                             Setting a negative number will make cookie expire after current session
     * @throws InvalidArgumentException
     */
    public function __construct(string $cookieName, int $ttl = 157766400)
    {
        // We cannot typehint for primitive types yet so therefore we check if the cookie name is a (valid) string.
        Assert::notEmpty($cookieName, 'The cookie name is invalid.');

        $this->cookieName = $cookieName;

        $this->ttl = $ttl;
    }

    /**
     * Parses any previous cookie and stores it internally
     */
    protected function parseExistingCookie(): void
    {
        if (is_array($this->data)) {
            return;
        }

        $cookiesContent = filter_input_array(INPUT_COOKIE);

        if (empty($cookiesContent) || !array_key_exists($this->cookieName, $cookiesContent)) {
            $this->data = [];
            return;
        }

        $deserializedCookie = json_decode($cookiesContent[$this->cookieName], true);

        if (is_null($deserializedCookie)) {
            $this->data = [];
            return;
        }

        $this->data = $deserializedCookie;
    }

    /**
     * {@inheritDoc}
     */
    protected function saveCookie(): bool
    {
        $this->parseExistingCookie();

        return setcookie($this->cookieName, json_encode($this->data), time() + $this->ttl, '/');
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $identifier): bool
    {
        Assert::string($identifier, 'Test identifier is invalid.');
        Assert::notEmpty($identifier, 'Test identifier is invalid.');

        $this->parseExistingCookie();

        return array_key_exists($identifier, $this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $identifier): mixed
    {
        if (!$this->has($identifier)) {
            return null;
        }

        return $this->data[$identifier];
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $identifier, mixed $value): bool
    {
        $this->has($identifier);

        if ('' === $value) {
            throw new InvalidArgumentException('Participation name is invalid.');
        }

        if (headers_sent()) {
            throw new RuntimeException('Headers have been sent. Cannot save cookie.');
        }

        $this->data[$identifier] = $value;

        return $this->saveCookie();
    }

    /**
     * {@inheritDoc}
     */
    public function all(): array
    {
        $this->parseExistingCookie();

        return $this->data;
    }

    /**
     * {@inheritDoc}
     */
    public function remove(string $identifier): mixed
    {
        $this->has($identifier);

        if (headers_sent()) {
            throw new RuntimeException('Headers have been sent. Cannot save cookie.');
        }

        $value = $this->get($identifier);

        if (is_null($value)) {
            return null;
        }

        unset($this->data[$identifier]);

        $this->saveCookie();

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function clear(): array
    {
        if (headers_sent()) {
            throw new RuntimeException('Headers have been sent. Cannot save cookie.');
        }

        $this->parseExistingCookie();
        $values = $this->data;
        $this->data = [];
        $this->saveCookie();

        return $values;
    }
}
