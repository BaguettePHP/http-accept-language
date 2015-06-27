<?php
namespace Teto\HTTP;
use Locale;

/**
 * HTTP `Accept-Language` locale object
 *
 * @package   Teto\HTTP*
 * @copyright Copyright (c) 2014 USAMI Kenta
 * @author    USAMI Kenta <tadsan@zonu.me>
 * @license   MIT License
 *
 * @property-read string $language
 * @property-read string $script
 * @property-read string $region
 * @property-read string $variant1
 * @property-read string $variant2
 * @property-read string $variant3
 * @property-read string $private1
 * @property-read string $private2
 * @property-read string $private3
 */
class AcceptLanguageLocale implements \ArrayAccess, \IteratorAggregate
{
    private $locale = [
        'language' => '',
        'script'   => '',
        'region'   => '',
        'variant1' => '',
        'variant2' => '',
        'variant3' => '',
        'private1' => '',
        'private2' => '',
        'private3' => '',
    ];

    /**
     * @param  array $locale
     * @link   http://php.net/manual/locale.composelocale.php
     */
    public function __construct(array $locale)
    {
        foreach ($locale as $l => $v) {
            $this->locale[$l] = $v;
        }
    }

    /**
     * @return array
     */
    public function getAsArray()
    {
        return $this->locale;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->locale);
    }

    /**
     * @throws \OutOfRangeException
     */
    public function __get($name)
    {
        if (!array_key_exists($name, $this->locale)) {
            throw new \OutOfRangeException($name);
        }

        return $this->locale[$name];
    }

    public function __isset($name)
    {
        return isset($this->locale[$name]);
    }

    /**
     * @throws \OutOfBoundsException
     */
    public function offsetGet($name)
    {
        if (!array_key_exists($name, $this->locale)) {
            throw new \OutOfBoundsException($name);
        }

        return $this->locale[$name];
    }

    public function offsetExists($offset)
    {
        return isset($this->locale[$offset]);
    }

    // AcceptLanguage class is immutable.
    public function __set($_name, $_value) { throw new \LogicException; }
    public function __unset($_name) { throw new \LogicException; }
    public function offsetSet($_offset, $_value) { throw new \LogicException; }
    public function offsetUnset($_offset) { throw new \LogicException; }
}
