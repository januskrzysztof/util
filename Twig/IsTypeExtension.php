<?php

namespace Tutto\Bundle\UtilBundle\Twig;

use Twig_Extension;
use Twig_SimpleTest;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

/**
 * Class CustomExtension
 * @package Tutto\CommonBundle\Twig
 */
class IsTypeExtension extends Twig_Extension {
    /**
     * @return array
     */
    public function getFunctions() {
        return [
            new Twig_SimpleFunction('isObject', [$this, 'isObject']),
            new Twig_SimpleFunction('isString', [$this, 'isString']),
            new Twig_SimpleFunction('isNumber', [$this, 'isNumber']),
            new Twig_SimpleFunction('isFloat', [$this, 'isFloat']),
            new Twig_SimpleFunction('isInt', [$this, 'isInt']),
            new Twig_SimpleFunction('isBoolean', [$this, 'isBoolean']),
            new Twig_SimpleFunction('isNull', [$this, 'isNull']),

        ];
    }

    /**
     * @return array
     */
    public function getFilters() {
        return [
            new Twig_SimpleFilter('isObject', [$this, 'isObject']),
            new Twig_SimpleFilter('isString', [$this, 'isString']),
            new Twig_SimpleFilter('isNumber', [$this, 'isNumber']),
            new Twig_SimpleFilter('isFloat', [$this, 'isFloat']),
            new Twig_SimpleFilter('isInt', [$this, 'isInt']),
            new Twig_SimpleFilter('isBoolean', [$this, 'isBoolean']),
            new Twig_SimpleFilter('isNull', [$this, 'isNull']),
            new Twig_SimpleFilter('set', [$this, 'set']),
        ];
    }

    /**
     * @return array
     */
    public function getTests() {
        return [
            new Twig_SimpleTest('object', [$this, 'isObject']),
            new Twig_SimpleTest('string', [$this, 'isString']),
            new Twig_SimpleTest('number', [$this, 'isNumber']),
            new Twig_SimpleTest('float', [$this, 'isFloat']),
            new Twig_SimpleTest('int', [$this, 'isInt']),
            new Twig_SimpleTest('boolean', [$this, 'isBoolean']),
            new Twig_SimpleTest('null', [$this, 'isNull'])
        ];
    }

    /**
     * @param mixed $object
     * @return bool
     */
    public function isObject($object) {
        return is_object($object);
    }

    /**
     * @param mixed $string
     * @return bool
     */
    public function isString($string) {
        return is_string($string);
    }

    /**
     * @param mixed $number
     * @return bool
     */
    public function isNumber($number) {
        return is_numeric($number);
    }

    /**
     * @param mixed $float
     * @return bool
     */
    public function isFloat($float) {
        return is_float($float);
    }

    /**
     * @param mixed $int
     * @return bool
     */
    public function isInt($int) {
        return is_int($int);
    }

    /**
     * @param mixed $boolean
     * @return bool
     */
    public function isBoolean($boolean) {
        return is_bool($boolean);
    }

    /**
     * @param mixed $null
     * @return bool
     */
    public function isNull($null) {
        return is_null($null);
    }

    /**
     * @return string The extension name
     */
    public function getName() {
        return 'tutto_common_custom';
    }
}