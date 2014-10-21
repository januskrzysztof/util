<?php

namespace Tutto\Bundle\UtilBundle\Logic;

use JsonSerializable;
use Serializable;
use ArrayAccess;
use Countable;
use IteratorAggregate;
use Traversable;
use ArrayIterator;

/**
 * Class Attributes
 * @package Tutto\Bundle\UtilBundle\Logic
 *
 * @Annotation()
 */
class Attributes implements Countable, JsonSerializable, Serializable, ArrayAccess, IteratorAggregate {
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = []) {
        $this->setAttributes($attributes);
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function setAttribute($name, $value) {
        $this->attributes[$name] = $value;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function addAttribute($name, $value) {
        $this->attributes[$name][] = $value;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes) {
        foreach ($attributes as $name => $value) {
            $this->setAttribute($name, $value);
        }
    }

    /**
     * @param array $attributes
     */
    public function addAttributes(array $attributes) {
        foreach ($attributes as $name => $value) {
            $this->addAttribute($name, $value);
        }
    }

    /**
     * @return array
     */
    public function getAttributes() {
        return $this->attributes;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getAttribute($name, $default = null) {
        return $this->hasAttribute($name) ? $this->attributes[$name] : $default;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasAttribute($name) {
        return isset($this->attributes[$name]);
    }

    /**
     * @param string $name
     */
    public function removeAttribute($name) {
        if ($this->hasAttribute($name)) {
            unset($this->attributes[$name]);
        }
    }

    public function clearAttributes() {
        $this->attributes = [];
    }

    /**
     * @return array
     */
    public function all() {
        return $this->getAttributes();
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null) {
        return $this->getAttribute($name, $default);
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value) {
        $this->setAttribute($name, $value);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has($name) {
        return $this->hasAttribute($name);
    }

    /**
     * Count elements of an object
     *
     * @return int
     */
    public function count() {
        return count($this->attributes);
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return mixed
     */
    function jsonSerialize() {
        return $this->attributes;
    }

    /**
     * String representation of object
     *
     * @return string
     */
    public function serialize() {
        return serialize($this->attributes);
    }

    /**
     * Constructs the object
     *
     * @param string $serialized
     * @return void
     */
    public function unserialize($serialized) {
        $this->attributes = unserialize($serialized);
    }

    /**
     * Whether a offset exists
     *
     * @param mixed $offset
     * @return boolean
     */
    public function offsetExists($offset) {
        return $this->hasAttribute($offset);
    }

    /**
     * Offset to retrieve
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset) {
        return $this->getAttribute($offset);
    }

    /**
     * Offset to set
     *
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value) {
        $this->setAttribute($offset, $value);
    }

    /**
     * Offset to unset
     *
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset) {
        $this->removeAttribute($offset);
    }

    /**
     * @param string $name
     * @return mixed
     */
    function __get($name) {
        return $this->get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    function __set($name, $value) {
        $this->set($name, $value);
    }

    /**
     * @param string $name
     * @return bool
     */
    function __isset($name) {
        return $this->has($name);
    }

    /**
     * @return string
     */
    function __toString() {
        $str = '';
        foreach ($this->all() as $key => $value) {
            if (!empty($value)) {
                if (is_array($value)) {
                    $value = implode(' ', $value);
                }

                $str.= "{$key}=\"{$value}\"";
            }
        }

        return trim($str);
    }

    /**
     * Retrieve an external iterator
     *
     * @return Traversable
     */
    public function getIterator() {
        return new ArrayIterator($this->attributes);
    }
}