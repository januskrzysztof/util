<?php

namespace Tutto\Bundle\UtilBundle\Exceptions;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Exception;

/**
 * Class BadUserClassException
 * @package Tutto\Bundle\UtilBundle\Exceptions
 */
class BadUserClassException extends AuthenticationException {
    /**
     * @param int $code
     * @param Exception $previous
     */
    public function __construct($code = 0, Exception $previous = null) {
        parent::__construct('BadUserClassException', $code, $previous);
    }
}