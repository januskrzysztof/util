<?php

namespace Tutto\Bundle\UtilBundle\Exceptions;

use Exception;

/**
 * Class ClassNotFoundException
 * @package Tutto\Bundle\UtilBundle\Exceptions
 */
class ClassNotFoundException extends Exception {
    /**
     * @param string $class
     */
    public function __construct($class) {
        parent::__construct("Class: '$class' not found.", 0, null);
    }
}