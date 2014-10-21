<?php

namespace Tutto\Bundle\UtilBundle\Logic;

use Symfony\Component\PropertyAccess\PropertyAccessor as BasePropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyPathInterface;

/**
 * Class PropertyAccessor
 * @package Tutto\CommonBundle\PropertyAccess
 */
class PropertyAccessor extends BasePropertyAccessor {
    /**
     * @param array|object $objectOrArray
     * @param string|PropertyPathInterface $propertyPath
     * @return mixed
     */
    public function getValue($objectOrArray, $propertyPath) {
        if (is_array($objectOrArray)) {
            $paths = explode('.', $propertyPath);
            $propertyPath = '';
            foreach ($paths as $path) {
                $propertyPath.= '['.trim($path, '[]').']';
            }
        }

        return parent::getValue($objectOrArray, $propertyPath);
    }
}